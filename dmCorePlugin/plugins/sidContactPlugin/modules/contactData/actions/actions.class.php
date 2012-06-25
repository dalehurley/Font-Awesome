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
            $infos['tel'] = $data['tel'];
            $infos['fax'] = $data['fax'];
            unset($data['tel']);
            unset($form['tel']);
            unset($data['fax']);
            unset($form['fax']);
            // on supprime le captcha qui est déjà validé
            unset($data['captcha']);
            unset($form['captcha']);
            // ajout du champ "infos" dans les données à mettre dans le form


            $data['infos'] = json_encode($infos);



            // réintégration du champ infos avant le bind
            $form->setWidget('infos', new sfWidgetFormTextarea());
            $form->setValidator('infos', new sfValidatorString());

            // on refait un bind pour mettre infos
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
    foreach ($fields as $field) {
      // field's widget
      switch ($field->type) {
        case 'text':
          $form->setWidget(dmString::slugify($field->name), new sfWidgetFormInput(
            json_decode($field->widget_options,true),
            json_decode($field->widget_attributes,true)   
            )
          );
          break;
        
        case 'textarea':
          # code...
          break;

        case 'radio':
          # code...
          break;

        case 'checkbox':
          # code...
          break;

        case 'select':
          # code...
          break;

                                              
        default:
          # code...
          break;
      }
      
      // field's help
      $form->getWidgetSchema()->setHelps(array(dmString::slugify($field->name) => $field->help));          

      // field's validator
      $form->setWidget( new sfValidatorChoice(
        json_decode($field->validator_options,true),
        json_decode($field->validator_messages,true)    
        )
      );


    }



// $form->setWidget('tel'.$fields[0]->type, new sfWidgetFormTextarea());
//           $form->setValidator('tel', new sfValidatorString());

//           $form->setWidget('fax', new sfWidgetFormTextarea());
//           $form->setValidator('fax', new sfValidatorString());

    return $form;



        // $this->widgetSchema['title'] = new sfWidgetFormSelect(array(
        //     'choices' => array('' => 'Choose') + $titles
        // ));
        // $this->validatorSchema['title'] = new sfValidatorChoice(array(
        //     'choices' => array_keys($titles),
        //     'required' => true),
        //      array(   
        //     'required' => 'Choose civility',
        // ));

        // if ($request->hasParameter($form->getName()) && $form->bindAndValid($request))
        // {
        //   $form->save();
        //   $this->redirectBack();
        // }

        //
  }
}
