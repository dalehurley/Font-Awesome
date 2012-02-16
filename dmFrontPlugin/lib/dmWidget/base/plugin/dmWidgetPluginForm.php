<?php

abstract class dmWidgetPluginForm extends dmWidgetBaseForm
{
 public function configure()
  {

    $this->widgetSchema['widthImage'] = new sfWidgetFormInputText(array('default' => 200, 'label' => 'Largeur en px'));
    $this->validatorSchema['widthImage'] = new dmValidatorCssSize(array(
      'required' => false
    ));

    $this->widgetSchema['heightImage'] = new sfWidgetFormInputText(array( 'label' => 'Hauteur en px'));
    $this->validatorSchema['heightImage'] = new dmValidatorCssSize(array(
      'required' => false
     ));
        
    $this->widgetSchema['withImage'] = new sfWidgetFormInputCheckbox(array('default'=> true));
    $this->validatorSchema['withImage']  = new sfValidatorBoolean();
   

    parent::configure();
  }
}