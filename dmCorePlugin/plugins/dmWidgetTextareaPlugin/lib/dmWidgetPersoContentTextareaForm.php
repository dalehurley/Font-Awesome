<?php

class dmWidgetPersoContentTextareaForm extends dmWidgetPluginForm {

  public function configure()
  {
    $this->widgetSchema['libelle'] = new sfWidgetFormInputText();
    $this->validatorSchema['libelle'] = new dmValidatorStringEscape (array(
      'required' => true
    ));

  
    $this->widgetSchema->setHelp('libelle', 'Votre texte');

     parent::configure();
  }





}