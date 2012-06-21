<?php

class myContactDataForm extends dmWidgetProjectForm
{
  public function configure()
  {
    parent::configure();

    $this->widgetSchema['ww']     = new sfWidgetFormInputText(array('label' => 'ww'));
    $this->validatorSchema['ww']  = new dmValidatorCssClasses(array('required' => false));
    

  }
}