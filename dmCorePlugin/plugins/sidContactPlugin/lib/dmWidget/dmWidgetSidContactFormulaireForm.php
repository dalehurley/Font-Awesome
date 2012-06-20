<?php

class dmWidgetSidContactFormulaireForm extends dmWidgetPluginForm
{

  public function configure()
  {
      parent::configure();
 
    $this->widgetSchema['formulaire'] = new sfWidgetFormInputText();
    $this->validatorSchema['formulaire'] = new sfValidatorString(array('required'=> false));
    $this->widgetSchema['formulaire']->setLabel('formulaire num');
    
	unset(
      $this['widthImage'], $this['heightImage'],$this['length'], $this['withImage'], $this['nbArticles'], $this['lien'], $this['titreBloc'], $this['chapo']
    );



  }

  
}