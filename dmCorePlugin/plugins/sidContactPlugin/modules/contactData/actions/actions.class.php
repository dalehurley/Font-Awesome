<?php
/**
 * Contact actions
 */
class contactDataActions extends myFrontModuleActions
{

  public function executeFormWidget(dmWebRequest $request)
  {

    // récupérer les variables du widget
    // recuperer l'id du widget qui appelle l'action
    $contactDataWidgets = array();
    foreach ($this->getPage()->getWidgets() as $widget) {
      if ($widget->getModule() == 'contactData' && $widget->getAction() == 'form'){
            $contactDataWidgets[] = $widget; 
          }
    }
    // s'il y'a plusieurs widgets de ce type sur la page alors on ne saura pas où trouver les paramètres du widget 
    // l'action executeFormWidget ne connait que la page, elle ne connait pas le widget qui l'a lancé 
    
    $form = new dmForm;
    $this->forms['SidContactData'] = $form;  // on renvoie un dmForm vide

    if (count($contactDataWidgets) == 1){

      $form = new SidContactDataForm();
      // 1) unset infos field
      unset($form['infos']);
      // 2) add fields present in contactField table
      $widgetValues = $contactDataWidgets[0]->getValues(); // seulement un widget contactData/form dans la page, donc on peut récupérer ses paramètres
      $idContactForm = $widgetValues['contactForm'][0];
      $fields = dmDb::table('SidContactField')->findByFormIdAndIsActive($idContactForm,true);

      if (count($fields)){

        $form->setWidget('tel', new sfWidgetFormInputText());
        $form->setWidget('fax', new sfWidgetFormInputText());


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




        if ($request->hasParameter($form->getName()))
        {
          $data = $request->getParameter($form->getName());

          // on supprimer les champs ajoutés dynamiquement pour les concaténer dans un json qui ira dans le champ "infos"
          var_dump($data);







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
            $form->save();

            $this->getUser()->setFlash('contact_form_valid', true);

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


}
