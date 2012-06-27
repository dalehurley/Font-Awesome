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
    $form = new dmForm;
    $this->forms['SidContactData'] = $form;  // on renvoie un dmForm vide

    // récupération du widget qui appelle l'action
    // Actuellement on vérifie qu'il n'y a qu'un seul widget "contactData form" dans la page pour récupérer ses infos
    // @TODO: le faire proprement, càd trouver le moyen de connaitre l'id du widget appelant ici
    $contactDataWidget = $this->getPage()->isWidgetUnique('contactData','form');
    if ($contactDataWidget){  // s'il n'y a qu'un seul widget contactData Form dans la page

      $form = new SidContactDataForm();
      // 1) unset infos field
      unset($form['infos']);
      
      // 2) get form fields if exist
      $fields = self::getContactFields($contactDataWidget);
      
      // 3) add fields present in contactField table, if present
      if ($fields){  // s'il y'a des champs définis en base dans la table contactField
        $form = self::addContactFields($form, $fields, $request);

        if ($request->hasParameter($form->getName()))
        {
          $data = $request->getParameter($form->getName());

          if($form->isCaptchaEnabled())
          {
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
              
              $infos[dmString::slugify($field->name)] = $data[dmString::slugify($field->name)]; // le tableau "$infos" reçoit chaque contactField
              
              /*********** debut gestion du champ "destinataire"  **************/
              // on ajoute l'adresse email plutot que l'id
              if (dmString::slugify($field->name) == 'destinataire'){  // si un champ se nomme "destinataire"
                // on vérifie que le model SidCabinetEquipe existe
                $tableSidCabinetEquipe = dmDb::table('SidCabinetEquipe');
                if (isset($tableSidCabinetEquipe)){
                  $equipe = $tableSidCabinetEquipe->findOneById($data[dmString::slugify($field->name)]); // on récupère l'objet equipe correspondant
                  $infos[dmString::slugify($field->name)] = $equipe->email;  // on stocke dans infos l'email plutot que l'id de l'objet equipe
                }
              }
              /*********** fin gestion du champ "destinataire"  **************/

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

            $this->getUser()->setFlash('sid_contact_form_valid', true);

            // $this->getService('dispatcher')->notify(new sfEvent($this, 'sid_contact_data.saved', array(
            //   'contact_data' => $form->getObject()
            // )));

            $this->redirectBack();
          }
        }
        
        // envoi des données du contactForm au component
        $widgetValues = $contactDataWidget->getValues(); // seulement un widget contactData/form dans la page, donc on peut récupérer ses paramètres
        $contactForm = dmDb::table('SidContactForm')->findOneById($widgetValues['contactForm'][0]); 
        $this->getRequest()->setAttribute('name', $contactForm->name);
        $this->getRequest()->setAttribute('description', $contactForm->description);

        // envoi du formulaire au component
        $this->forms['SidContactData'] = $form;
      } 

    }
  }


  public function getContactFields($contactDataWidget){
      $widgetValues = $contactDataWidget->getValues(); // seulement un widget contactData/form dans la page, donc on peut récupérer ses paramètres
      if (isset($widgetValues['contactForm'])){
        $idContactForm = $widgetValues['contactForm'][0];
        if ($idContactForm != ''){
          $fields = dmDb::table('SidContactField')->findByFormIdAndIsActive($idContactForm,true); // seulement les champs actifs
          return $fields;
        } else {
          return false;
        }
      } else {
        return false;
      }
  }


  public function addContactFields($form, $fields, $request){

    $fieldsPositions = array(
        0 => 'id',
        1 => 'email',
        2 => '_csrf_token'
      );

    foreach ($fields as $field) {
      /************* get position field ***********/
      // abs(position) >= 0. So we add 3 because 2 is the position of _crsf_token
      $fieldsPositions[abs($field->position) + 3] = dmString::slugify($field->name); 

      /************* selected destinataire in list choice if passed in request like "example.com/?destinataire=8" ************/
      if (dmString::slugify($field->name) == 'destinataire'){  // si un champ se nomme "destinataire"
        if ($request->hasParameter('dest'))
        {
          $idEquipe = $request->getParameter('dest');
          $form->setDefault('destinataire', $idEquipe);
        }
      }

      /************* field's widget ***************/
      $widgetOptions = json_decode($field->widget_options,true);
      $widgetAttributes = json_decode($field->widget_attributes,true);
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
      $validatorOptions = json_decode($field->validator_options,true);
      $validatorMessages = json_decode($field->validator_messages,true);
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
    }

    // sort fields with contactField's position value
    // add captacha at the end if exists
    if ($form->isCaptchaEnabled()) {
      $fieldsPositions[] = 'captcha';
    }
    $form->getWidgetSchema()->setPositions($fieldsPositions);

    return $form;

  }
}
