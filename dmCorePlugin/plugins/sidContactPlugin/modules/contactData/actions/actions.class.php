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

    // analyse et traitement des validateurs du formulaire
    $form->bind($data, $request->getFiles($form->getName()));

    if ($form->isValid()){
      self::recordForm($form, $data, $fields, $request);
    }
  }

  public function recordForm($form, $data, $fields, $request){
      // on supprime les champs ajoutés dynamiquement pour les concaténer dans un json qui ira dans le champ "infos"
      $infos = array();
      
      foreach ($fields as $field) {
        $infos[dmString::slugify($field->name)] = $data[dmString::slugify($field->name)]; // le tableau "$infos" reçoit la valeur de chaque contactField
        
        // on ajoute l'adresse email plutot que l'id
        if (dmString::slugify($field->name) == 'destinataire'){  // si un champ se nomme "destinataire"
          if (isset($data[dmString::slugify($field->name)])){
            $equipe = dmDb::table('SidCabinetEquipe')->findOneById($data[dmString::slugify($field->name)]); // on récupère l'objet equipe correspondant
            $infos[dmString::slugify($field->name)] = $equipe->email;  // on stocke dans infos l'email plutot que l'id de l'objet equipe
          }
        }

        // on supprime le field de data et du formulaire, ne restera que infos dans le formulaire
        unset($data[dmString::slugify($field->name)]);
        unset($form[dmString::slugify($field->name)]);
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

      // on previent le template que c'est ok par un flash
      $this->getUser()->setFlash('sid_contact_form_valid', true);
      
      // on crée l'event qui sera tracké dans dmFrontApplicationConfiguration !!! Ne fonction pas sur le serveur de production!!!!
      // $this->getService('dispatcher')->notify(new sfEvent($this, 'sid_contact_data.saved', array(
      //   'contact_data' => $form->getObject()
      // )));
      self::sendEmail($form->getObject());

      $this->redirectBack();
  }


  public function sendEmail($contact)
  {
    // do something with the freshly saved $contact
    $message = '';
    $infos = json_decode($contact->infos, true);
    
    // calcul de la plus longue $key
    $keysArray = array_keys($infos);
    $lengthsKeysArray = array_map('strlen', $keysArray);
    $longuestKey = max($lengthsKeysArray);
    // ajout d'espace après le libellé du champ pour aligner les ":"
    foreach ($infos as $key => $value) {
      $message .= $key.str_repeat(' ', $longuestKey - strlen($key))." :".$value."
";
    }

    // si le champ "destinataire" est présent dans le contact alors 
    if (isset($infos['destinataire']) && $infos['destinataire'] != '') {
      $destEmail = $infos['destinataire'];  // on l'envoie au destinataire choisi dans le formulaire de contact
    } else {
      $destEmail = dmConfig::get('site_email'); // sinon on envoie au site_email
    }

    dm::enableMailer();
    try {
      $result = sfContext::getInstance()->getMailer()->composeAndSend(
        array(
          dmConfig::get('site_email_sender') => dmConfig::get('site_name')),
          $destEmail, 
          dmConfig::get('site_name').' - Contact', $message
        );
      $swiftSend = ($result)? 'send ok' : 'send no';
      sfContext::getInstance()->getUser()->setFlash('mail', 'connect ok, send to -> '.$destEmail.' ('.$swiftSend.')');
    }
    catch (Exception $e) {
      $exceptionMessage = 'Error '.$e->getMessage().'<br/>Code: '.$e->getCode().'<br/>File: '.$e->getFile().':'.$e->getLine();
      sfContext::getInstance()->getUser()->setFlash('mail', 'error');
      sfContext::getInstance()->getUser()->setFlash('mail_exception', $exceptionMessage);
    }
  }




  public function addContactFields($form, $fields, $request){

    $fieldsPositions = array(
        0 => 'id',
        1 => 'email',
        2 => '_csrf_token'
      );

    $listDest = '<br/>';

    foreach ($fields as $field) {
      $addField = true;

      // on récupère les options, attributs et messages du field
      $widgetOptions = json_decode($field->widget_options,true);
      $widgetAttributes = json_decode($field->widget_attributes,true);

      $validatorOptions = json_decode($field->validator_options,true);
      $validatorMessages = json_decode($field->validator_messages,true);      

      // on ajoute le champ destinataire de façon spécifique
      if (dmString::slugify($field->name) == 'destinataire'){
        // y'a t-il des objets equipes actifs et avec une adresse email?
        if (!self::hasCabinetEquipe()) {
          $addField = false;
        } else {
          // ajout pour le composant de la liste des destinataires
          $query = Doctrine_Query::create()
            ->from('SidCabinetEquipe e')
            ->withI18n()
            ->where('e.is_active = ?', true)
            ->addWhere('eTranslation.email <> ?', '');
          $dests = $query->execute();
          foreach ($dests as $dest) {
            $listDest .= '&nbsp;&nbsp;'.$dest->email.'<br/>';
          }
          // on parametre le widget destinataire
          $arrayFormWidgetOptions = self::addDestinataireField($form, $request, $widgetOptions, $validatorOptions, $field);
          $form = $arrayFormWidgetOptions['form'];
          $widgetOptions = $arrayFormWidgetOptions['widgetOptions'];
          $validatorOptions = $arrayFormWidgetOptions['validatorOptions'];
          $field = $arrayFormWidgetOptions['field'];
        }
      }

      if ($addField){

        if (!is_array($widgetOptions)) $widgetOptions = array();
        if (!is_array($widgetAttributes)) $widgetAttributes = array();

        /************* field's widget ***************/
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

    // list des destinaires concaténée
    $this->getRequest()->setAttribute('destinataires', $listDest);

    // add captacha at the end if exists
    if ($form->isCaptchaEnabled()) {
      $fieldsPositions[] = 'captcha';
    }
    // sort fields with contactField's position value
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


/* datas saved for a basic form contact 


INSERT INTO sid_contact_form (id, position) VALUES (1, -1);

INSERT INTO sid_contact_form_translation (id, name, description, lang, version, created_at, updated_at) VALUES (1,'Contactez-nous','<br />\r\n','fr',3,'2012-06-26 14:13:51','2012-06-27 14:03:48');

INSERT INTO sid_contact_form_translation_version (id, lang, name, version) VALUES (1,'fr','Contactez-nous',1);

INSERT INTO sid_contact_field (id, widget_type, widget_options, widget_attributes, validator_type, validator_options, validator_messages, form_id, is_active, is_required, position) VALUES (1,'sfWidgetFormInputText','','','sfValidatorNumber','','{\"invalid\" : \"Numéro de téléphone invalide\"}',1,1,0,9),(2,'sfWidgetFormInputText','','','sfValidatorNumber','','{\"invalid\" : \"Numéro de fax invalide\"}',1,1,0,10),(3,'sfWidgetFormInputText','','','sfValidatorString','','',1,1,0,1),(4,'sfWidgetFormInputText','','','sfValidatorNumber','','{\"invalid\" : \"Code postal invalide\"}',1,1,0,7),(5,'sfWidgetFormChoice','{\"choices\" : {\"Monsieur\" : \"Monsieur\", \"Madame\" : \"Madame\", \"Mademoiselle\" : \"Mademoiselle\"}}','','sfValidatorChoice','{\"choices\" : {\"Monsieur\" : \"Monsieur\", \"Madame\" : \"Madame\", \"Mademoiselle\" : \"Mademoiselle\"}}','',1,1,1,2),(6,'sfWidgetFormInputText','','','sfValidatorString','{\"min_length\" : \"3\"}','{\"min_length\" : \"\'%value\'% est trop court (%min_length% caractères minimum)\"}',1,1,1,3),(7,'sfWidgetFormInputText','','','sfValidatorString','{\"min_length\" : \"3\"}','{\"min_length\" : \"\'%value%\' est trop court (%min_length% caractères minimum)\"}',1,1,0,4),(8,'sfWidgetFormInputText','','','sfValidatorString','','',1,1,0,5),(9,'sfWidgetFormInputText','','','sfValidatorString','','',1,1,0,6),(10,'sfWidgetFormInputText','','','sfValidatorString','','',1,1,0,8),(11,'sfWidgetFormTextarea','','','sfValidatorString','','',1,1,1,11);

INSERT INTO sid_contact_field_translation (id, name, help, lang, version, created_at, updated_at, position) VALUES (1,'Téléphone','','fr',3,'2012-06-26 14:14:21','2012-06-27 16:42:32',-1),(2,'Fax','','fr',3,'2012-06-26 14:15:18','2012-06-27 16:42:39',-2),(3,'Destinataire','','fr',3,'2012-06-26 17:28:16','2012-06-27 15:35:17',-3),(4,'Code postal','','fr',2,'2012-06-26 17:31:06','2012-06-26 17:31:06',-4),(5,'Civilité','','fr',2,'2012-06-27 14:34:39','2012-06-27 14:34:39',-5),(6,'Nom','','fr',2,'2012-06-27 15:20:19','2012-06-27 15:20:19',-6),(7,'Prénom','','fr',2,'2012-06-27 15:21:49','2012-06-27 15:21:49',-7),(8,'Fonction','','fr',2,'2012-06-27 15:23:44','2012-06-27 15:23:44',-8),(9,'Adresse','','fr',2,'2012-06-27 15:24:16','2012-06-27 15:24:16',-9),(10,'Ville','','fr',2,'2012-06-27 15:26:46','2012-06-27 15:26:46',-10),(11,'Message','','fr',2,'2012-06-27 15:30:33','2012-06-27 15:30:33',-11);

INSERT INTO sid_contact_field_translation_version (id, lang, name, help, version) VALUES (1,'fr','Téléphone','Le num&eacute;ro de t&eacute;l&eacute;phone',1),(1,'fr','Téléphone','Le num&eacute;ro de t&eacute;l&eacute;phone',2),(1,'fr','Téléphone','',3),(2,'fr','Fax','Le num&eacute;ro de fax',1),(2,'fr','Fax','Le num&eacute;ro de fax',2),(2,'fr','Fax','',3),(3,'fr','Destinataire','Destinataire',1),(3,'fr','Destinataire','Destinataire',2),(3,'fr','Destinataire','',3),(4,'fr','Code postal','',1),(4,'fr','Code postal','',2),(5,'fr','Civilité','',1),(5,'fr','Civilité','',2),(6,'fr','Nom','',1),(6,'fr','Nom','',2),(7,'fr','Prénom','',1),(7,'fr','Prénom','',2),(8,'fr','Fonction','',1),(8,'fr','Fonction','',2),(9,'fr','Adresse','',1),(9,'fr','Adresse','',2),(10,'fr','Ville','',1),(10,'fr','Ville','',2),(11,'fr','Message','',1),(11,'fr','Message','',2);


*/

