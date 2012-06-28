<?php
/**
 * Contact actions
 */
class contactDataActions extends myFrontModuleActions
{

  public function executeFormWidget(dmWebRequest $request)
  {
    // s'il y'a plusieurs widgets de ce type sur la page alors on ne saura pas où trouver les paramètres du widget 
    // l'action executeFormWidget ne connait que la page, elle ne connait pas le widget qui l'a lancé   
    // récupération du widget qui appelle l'action 
    // Actuellement on vérifie qu'il n'y a qu'un seul widget "contactData form" dans la page pour récupérer ses infos
    // @TODO: le faire proprement, càd trouver le moyen de connaitre l'id du widget appelant ici : pas possible autrement
    $contactDataWidget = $this->getPage()->isWidgetUnique('contactData','form');

    if ($contactDataWidget){  // s'il n'y a qu'un seul widget contactData Form dans la page
      self::buildForm($contactDataWidget, $request);
    } else {
      $this->getRequest()->setAttribute('error', array('Error' => 'There\'s Multiple contact widgets in this page. Need only one to work.'));
      $this->forms['SidContactData'] = new dmForm;  // on renvoie un dmForm vide
    }
  }

  public function buildForm($contactDataWidget, $request){
      
    $form = new SidContactDataForm();
    // 1) unset infos field
    unset($form['infos']);
    
    // 2) get form's fields if exist
    $fields = self::getContactFieldsOrderedByPosition($contactDataWidget);
    
    // 3) add fields present in contactField table, if present
    if ($fields){  // s'il y'a des champs définis en base dans la table contactField
      $form = self::addContactFields($form, $fields, $request);
     
      if ($request->hasParameter($form->getName())){
        self::manageForm($form, $fields, $request); 
      }

      // envoi des données du contactForm au component
      $widgetValues = $contactDataWidget->getValues(); // seulement un widget contactData/form dans la page, donc on peut récupérer ses paramètres
      $contactForm = dmDb::table('SidContactForm')->findOneById($widgetValues['contactForm'][0]); 
      $this->getRequest()->setAttribute('name', $contactForm->name);
      $this->getRequest()->setAttribute('description', $contactForm->description);
      // envoi du formulaire au component
      $this->forms['SidContactData'] = $form;

    } else {
      $this->getRequest()->setAttribute('error', array('Error' => 'There\'s no form selected or no fields in the selected form.'));
      $this->forms['SidContactData'] = new dmForm;  // on renvoie un dmForm vide
    }

  }

  public function getContactFieldsOrderedByPosition($contactDataWidget){
      $widgetValues = $contactDataWidget->getValues(); // seulement un widget contactData/form dans la page, donc on peut récupérer ses paramètres
      if (isset($widgetValues['contactForm'])){
        $idContactForm = $widgetValues['contactForm'][0];
        if ($idContactForm != ''){
          // seulement les champs actifs et triés par position
          $fields = dmDb::table('SidContactField')
          ->createQuery('a')
          ->where('a.form_id = ? ',$idContactForm)
          ->addWhere('a.is_active = ?',true)
          ->orderBy('a.position ASC')
          ->execute();

          return $fields;
        } else {
          return false;
        }
      } else {
        return false;
      }
  }

  public function manageForm($form, $fields, $request){
    
    $data = $request->getParameter($form->getName()); // récupération des données du formulaire
    // traitement du captcha
    if($form->isCaptchaEnabled()){
      $data = array_merge($data, array('captcha' => array(
        'recaptcha_challenge_field' => $request->getParameter('recaptcha_challenge_field'),
        'recaptcha_response_field'  => $request->getParameter('recaptcha_response_field'),
      )));
    }

    $form->bind($data, $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      // on supprimer les champs ajoutés dynamiquement pour les concaténer dans un json qui ira dans le champ "infos"
      $infos = array();
      foreach ($fields as $field) {
        $addField = true;
        $infos[dmString::slugify($field->name)] = $data[dmString::slugify($field->name)]; // le tableau "$infos" reçoit la valeur de chaque contactField
        /*********** debut gestion du champ "destinataire"  **************/
        // on ajoute l'adresse email plutot que l'id
        if (dmString::slugify($field->name) == 'destinataire'){  // si un champ se nomme "destinataire"
          // y'a t-il des object equipes actif et avec une adresse email?
          if (!self::hasCabinetEquipe()) {
            $addField = false;
          } else {
            $equipe = dmDb::table('SidCabinetEquipe')->findOneById($data[dmString::slugify($field->name)]); // on récupère l'objet equipe correspondant
            $infos[dmString::slugify($field->name)] = $equipe->email;  // on stocke dans infos l'email plutot que l'id de l'objet equipe
          }
        }
        /*********** fin gestion du champ "destinataire"  **************/
        if ($addField){
          // on supprime le field de data et du formulaire, ne restera que infos dans le formulaire
          unset($data[dmString::slugify($field->name)]);
          unset($form[dmString::slugify($field->name)]);
        }
      }
      // on supprime le captcha qui est déjà validé
      unset($data['captcha']);
      unset($form['captcha']);
      // ajout du champ "infos" dans les données à mettre dans le form
      $data['infos'] = json_encode($infos);
      // réintégration du champ infos avant le bind
      $form->setWidget('infos', new sfWidgetFormTextarea());
      $form->setValidator('infos', new sfValidatorString());
      // on refait un bind pour :
      // - mettre infos 
      // - supprimer les champs additionnels
      $form->bind($data, $request->getFiles($form->getName()));
      $form->save();
      $this->getUser()->setFlash('sid_contact_form_valid', true);
      $this->getService('dispatcher')->notify(new sfEvent($this, 'sid_contact_data.saved', array(
        'contact_data' => $form->getObject()
      )));
      $this->redirectBack();
    }
  }

  public function addContactFields($form, $fields, $request){

    $fieldsPositions = array(
        0 => 'id',
        1 => 'email',
        2 => '_csrf_token'
      );

    foreach ($fields as $field) {
      $addField = true;

      /************* field's widget ***************/
      $widgetOptions = json_decode($field->widget_options,true);
      $widgetAttributes = json_decode($field->widget_attributes,true);

      $validatorOptions = json_decode($field->validator_options,true);
      $validatorMessages = json_decode($field->validator_messages,true);      

      // on ajoute le champ destinataire de façon spécifique
      if (dmString::slugify($field->name) == 'destinataire'){
        // y'a t-il des object equipes actif et avec une adresse email?
        if (!self::hasCabinetEquipe()) $addField = false;
        $arrayFormWidgetOptions = self::addDestinataireField($form,$request, $widgetOptions, $validatorOptions, $field);
        $form = $arrayFormWidgetOptions['form'];
        $widgetOptions = $arrayFormWidgetOptions['widgetOptions'];
        $validatorOptions = $arrayFormWidgetOptions['validatorOptions'];
        $field = $arrayFormWidgetOptions['field'];
      }

      if ($addField){

        if (!is_array($widgetOptions)) $widgetOptions = array();
        if (!is_array($widgetAttributes)) $widgetAttributes = array();

        $form->setWidget(dmString::slugify($field->name), new $field->widget_type(
          $widgetOptions,
          $widgetAttributes  
          )
        );

        /************** field's label *************/
        $form->getWidgetSchema()->setLabel(dmString::slugify($field->name), $field->name);

        /************** field's help **************/
        $form->getWidgetSchema()->setHelp(dmString::slugify($field->name), $field->help);          

        /************** field's validator *************/
        if (!is_array($validatorOptions)) $validatorOptions = array();
        if (!is_array($validatorMessages)) $validatorMessages = array();

        // add required field
        if ($field->is_required) { 
          $validatorOptions = array_merge($validatorOptions,array('required' => true));
        } else {
          $validatorOptions = array_merge($validatorOptions,array('required' => false));
        }

        $form->setValidator( dmString::slugify($field->name), new $field->validator_type(
          $validatorOptions,
          $validatorMessages    
          )
        );

        /************* get position field ***********/
        // fields are sorted by position, we just push after the 3 first fields (id, email & csrf)
        $fieldsPositions[] = dmString::slugify($field->name);         

      }
    }

    // sort fields with contactField's position value
    // add captacha at the end if exists
    if ($form->isCaptchaEnabled()) {
      $fieldsPositions[] = 'captcha';
    }
    $form->getWidgetSchema()->setPositions($fieldsPositions);

    return $form;

  }

  public function addDestinataireField($form, $request, $widgetOptions, $validatorOptions, $field){
    
    // 1) selected destinataire in list choice if passed in request like "example.com/?destinataire=8" 
    if ($request->hasParameter('dest'))
    {
      $idEquipe = $request->getParameter('dest');
      $form->setDefault('destinataire', $idEquipe); // on met le select sur l'idEquipe
    }

    // 2) on ajoute l'option query pour n'avoir que les objets equipe qui ont une adresse email
    $query = Doctrine_Query::create()->from('SidCabinetEquipe e')->withI18n()->where('e.is_active = ?', true)->addWhere('eTranslation.email <> ?', '');
    $widgetOptions['query'] = $query;
    $widgetOptions['model'] = 'SidCabinetEquipe';
    $widgetOptions['add_empty'] = '';
    $widgetOptions['method'] = 'infoEquipe';
    $validatorOptions['model'] = 'SidCabinetEquipe';

    // 3) $field->widget_type doit etre sfWidgetFormDoctrineChoice
    $field->widget_type = 'sfWidgetFormDoctrineChoice';
    $field->validator_type = 'sfValidatorDoctrineChoice';    

    return array('form' => $form, 'widgetOptions' => $widgetOptions, 'validatorOptions' => $validatorOptions, 'field' => $field);
  }

  public function hasCabinetEquipe(){
    
    $cabinetEquipe = array();
    $tableSidCabinetEquipe = dmDb::table('SidCabinetEquipe');
    if (isset($tableSidCabinetEquipe)){
      $cabinetEquipe = Doctrine_Query::create()->from('SidCabinetEquipe e')->withI18n()->where('e.is_active = ?', true)->addWhere('eTranslation.email <> ?', '')->execute();
    } else {
      return false;
    }

    return count($cabinetEquipe);
  }


}
