<?php

class dmWidgetSidContactFormulaireView extends dmWidgetPluginView
{

  public function configure()
  {
    parent::configure();
    
    $this->addRequiredVar(array(
      'formulaire'
    ));
  }
 

  protected function doRender()
  {
    $vars = $this->getViewVars();


    $form = new SidContactDataForm();
        
    // if ($request->hasParameter($form->getName()) && $form->bindAndValid($request))
    // {
    //   $form->save();
    //   $this->redirectBack();
    // }
    







    return $this->getHelper()->renderPartial('contactData', 'formulaire', array(
                'formulaire' => $vars['formulaire'],
                'form' => $form
                ));  


  }
  

}
