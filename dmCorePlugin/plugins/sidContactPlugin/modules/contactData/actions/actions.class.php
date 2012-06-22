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
    $test = $this->getDmModule().' - '.$this->getDmAction().' - '.$this->getPage();



    $form = new SidContactDataForm();
    // 1) unset infos fields
    // 2) add fields presents in contactField table

    $form->setWidget('grgrg>'.$test, new sfWidgetFormInputText());


        
    if ($request->hasParameter($form->getName()) && $form->bindAndValid($request))
    {
      $form->save();
      $this->redirectBack();
    }
    
    $this->forms['SidContactData'] = $form;

  }


}
