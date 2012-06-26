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

    $contactDataWidget = $this->getPage()->isWidgetUnique('contactData','form');
    if ($contactDataWidget){  // s'il n'y a qu'un seul widget contactData Form dans la page

      $form = new SidContactDataForm();
      // 1) unset infos field
      unset($form['infos']);
      
      // 2) get form fields if exist
      $fields = self::getContactFields($contactDataWidget);
      
      // 3) add fields present in contactField table, if present
      if ($fields){  // s'il y'a des champs définis en base dans la table contactField
        $form = self::addContactFields($form, $fields);

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
              $infos[dmString::slugify($field->label)] = $data[dmString::slugify($field->label)];
              unset($data[dmString::slugify($field->label)]);
              unset($form[dmString::slugify($field->label)]);
            }

            // on supprime le captcha qui est déjà validé
            unset($data['captcha']);
            unset($form['captcha']);

            // ajout du champ "infos" dans les données à mettre dans le form
            $data['infos'] = json_encode($infos);

            // réintégration du champ infos avant le bind
            $form->setWidget('infos', new sfWidgetFormTextarea());
            $form->setValidator('infos', new sfValidatorString());

            // on refait un bind pour mettre infos et supprimer les champs additionnels
            $form->bind($data, $request->getFiles($form->getName()));

            $form->save();

            $this->getUser()->setFlash('sid_contact_form_valid', true);

            // $this->getService('dispatcher')->notify(new sfEvent($this, 'sid_contact_data.saved', array(
            //   'contact_data' => $form->getObject()
            // )));

            $this->redirectBack();
          }
        }
        
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


  public function addContactFields($form, $fields){

    $fieldsPositions = array(
        0 => 'id',
        1 => 'email',
        2 => '_csrf_token'
      );

    foreach ($fields as $field) {
      /************* get position field ***********/
      if ($field->position){
        $fieldsPositions[$field->position + 4] = dmString::slugify($field->label);
      } 

      /************* field's widget ***************/
      $widgetOptions = json_decode($field->widget_options,true);
      $widgetAttributes = json_decode($field->widget_attributes,true);
      if (!is_array($widgetOptions)) $widgetOptions = array();
      if (!is_array($widgetAttributes)) $widgetAttributes = array();

      $form->setWidget(dmString::slugify($field->label), new $field->widget_type(
        $widgetOptions,
        $widgetAttributes  
        )
      );

      /************** field's label *************/
      $form->getWidgetSchema()->setLabel(dmString::slugify($field->label), $field->label);

      /************** field's help **************/
      $form->getWidgetSchema()->setHelps(array(dmString::slugify($field->label) => $field->help));          

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

      $form->setValidator( dmString::slugify($field->label), new $field->validator_type(
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
