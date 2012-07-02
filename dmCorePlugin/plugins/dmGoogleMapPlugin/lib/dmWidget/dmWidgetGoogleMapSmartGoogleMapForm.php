<?php

class dmWidgetGoogleMapSmartGoogleMapForm extends dmWidgetPluginForm
{

  public function configure()
  {
      parent::configure();    
      
      $this->widgetSchema['smartGoogleMap'] = new sfWidgetFormInputHidden(array(),array('value' => 'true'));
      $this->validatorSchema['smartGoogleMap'] = new sfValidatorBoolean(array('required' => false));
      
      $this->widgetSchema['withResume'] = new sfWidgetFormInputCheckbox(array('label' => 'Afficher le résumé de l\'implantation', 'default' => true));
      $this->validatorSchema['withResume'] = new sfValidatorBoolean(array('required' => false));
//      $this->widgetSchema['withResume']->setLabel('Afficher le résumé de l\'implantation');
      
      $this->widgetSchema['mapWidth'] = new sfWidgetFormInputText(array('label' => 'Largeur de la carte', 'default' => 0));
      $this->validatorSchema['mapWidth'] = new sfValidatorInteger(array(
                'required' => false,
                ));
      $this->widgetSchema->setHelp('mapWidth','Largeur de la carte : <br /> - Maestro : 494 <br /> - Tenor : 622 <br /> - Co-pilote : 650 <br /> - Opera : 686');
      $this->widgetSchema->setHelp('mapHeight','Hauteur de la carte : <br /> - Maestro : 252 <br /> - Tenor : 324 <br /> - Co-pilote : 324 <br /> - Opera : 360');
      
      $this->widgetSchema['mapHeight'] = new sfWidgetFormInputText(array('label' => 'Hauteur de la carte', 'default' => 0));
      $this->validatorSchema['mapHeight'] = new sfValidatorInteger(array(
                'required' => false,
                ));
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
    return $this->getHelper()->renderPartial('dmWidgetGoogleMap', 'smartGoogleMapForm', array(
      'form' => $this,
      'baseTabId' => 'dm_widget_google_map_smart_google_map_'.$this->dmWidget->get('id')
    ));
  }

  public function getWidgetValues()
  {
    $values = parent::getWidgetValues();
    
    return $values;
  }
}