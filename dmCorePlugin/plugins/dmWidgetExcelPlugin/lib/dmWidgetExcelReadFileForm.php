<?php

class dmWidgetExcelReadFileForm extends dmWidgetPluginForm {

  public function configure()
  {
    $this->widgetSchema['libelle'] = new sfWidgetFormInputText();
    $this->validatorSchema['libelle'] = new dmValidatorStringEscape (array(
      'required' => true
    ));

  
    $this->widgetSchema->setHelp('libelle', 'Le nom du fichier présent dans /uploads/excel/');

     parent::configure();
  }





}