<?php

class dmWidgetGoogleMapShowForm extends dmWidgetPluginForm
{

  public function configure()
  {
      parent::configure();
    //création d'une liste déroulante comportant les adresses du cabinet

//    $this->widgetSchema['address'] = new sfWidgetFormDoctrineChoice(array('multiple'=> false, 'model'=>'SidCoordName', 'method'=>'cabinet_ville'));
//    $this->validatorSchema['address'] = new sfValidatorDoctrineChoice(array('required'=> true, 'model'=>'SidCoordName'));
    $this->widgetSchema['address'] = new sfWidgetFormInputText();
    $this->validatorSchema['address'] = new sfValidatorString(array('required'=> false));
    $this->widgetSchema['address']->setLabel('Search a place');
    

    $this->widgetSchema['mapTypeId'] = new sfWidgetFormSelect(array(
      'choices' => dmArray::valueToKey($this->getMapTypeIds())
    ));
    $this->validatorSchema['mapTypeId'] = new sfValidatorChoice(array(
      'choices' => $this->getMapTypeIds()
    ));
    $this->widgetSchema['mapTypeId']->setLabel('Map type');

    $this->widgetSchema['zoom'] = new sfWidgetFormSelect(array(
      'choices' => dmArray::valueToKey($this->getZooms())
    ));
    $this->validatorSchema['zoom'] = new sfValidatorChoice(array(
      'choices' => $this->getZooms()
    ));

    $this->widgetSchema['navigationControl'] = new sfWidgetFormInputCheckbox();
    $this->validatorSchema['navigationControl'] = new sfValidatorBoolean();
    $this->widgetSchema['navigationControl']->setLabel('Navigation');

    $this->widgetSchema['mapTypeControl'] = new sfWidgetFormInputCheckbox();
    $this->validatorSchema['mapTypeControl'] = new sfValidatorBoolean();
    $this->widgetSchema['mapTypeControl']->setLabel('Map type');

    $this->widgetSchema['scaleControl'] = new sfWidgetFormInputCheckbox();
    $this->validatorSchema['scaleControl'] = new sfValidatorBoolean();
    $this->widgetSchema['scaleControl']->setLabel('Scale');

    $this->widgetSchema['width'] = new sfWidgetFormInputText(array(), array('size' => 5));
    $this->validatorSchema['width'] = new dmValidatorCssSize(array(
      'required' => false
    ));

    $this->widgetSchema['height'] = new sfWidgetFormInputText(array(), array('size' => 5));
    $this->validatorSchema['height'] = new dmValidatorCssSize(array(
      'required' => false
    ));

    $this->widgetSchema['splash'] = new sfWidgetFormInputText();
    $this->validatorSchema['splash'] = new sfValidatorString(array(
      'required' => false
    ));
    $this->widgetSchema['idCabinet'] = new sfWidgetFormInput();
    $this->validatorSchema['idCabinet'] = new sfValidatorDoctrineChoice(array(
      'required' => false,
      'model' => 'SidCoordName'
    ));
    $this->widgetSchema->setHelp('splash', 'Display a message while the map is loading');
    
    $this->widgetSchema['withResume'] = new sfWidgetFormInputCheckbox();
    $this->validatorSchema['withResume'] = new sfValidatorBoolean();
    $this->widgetSchema['withResume']->setLabel('Afficher le résumé de l\'implantation');
    
//    $this->widgetSchema['smartGoogleMap'] = new sfWidgetFormInputHidden(array('default' => false));
//    $this->validatorSchema['smartGoogleMap'] = new sfValidatorBoolean(array('required' => false));
    
    $this->widgetSchema->setHelp('length','Longueur du texte avant de le tronquer, 0 pour voir tout le texte');
    if(!$this->getDefault('width'))
    {
      $this->setDefault('width', '350px');
    }
    if(!$this->getDefault('height'))
    {
      $this->setDefault('height', '250px');
    }
    if(!$this->getDefault('mapTypeId'))
    {
      $this->setDefault('mapTypeId', 'roadmap');
    }
    if(!$this->getDefault('zoom'))
    {
      $this->setDefault('zoom', '14');
    }


    
  }

  protected function getMapTypeIds()
  {
    return array('roadmap', 'satellite', 'hybrid', 'terrain');
  }

  protected function getZooms()
  {
    return range(2, 20);
  }

  public function getStylesheets()
  {
    return array(
      'lib.ui-tabs'
    );
  }

  public function getJavascripts()
  {
    return array(
      'lib.ui-tabs',
      'core.tabForm',
      'dmGoogleMapPlugin.widgetShowForm'
    );
  }

  protected function renderContent($attributes)
  {
    return $this->getHelper()->renderPartial('dmWidgetGoogleMap', 'showForm', array(
      'form' => $this,
      'baseTabId' => 'dm_widget_google_map_'.$this->dmWidget->get('id')
    ));
  }

  public function getWidgetValues()
  {
    $values = parent::getWidgetValues();
    
    return $values;
  }
}