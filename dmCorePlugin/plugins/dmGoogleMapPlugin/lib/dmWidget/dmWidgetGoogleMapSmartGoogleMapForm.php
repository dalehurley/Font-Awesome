<?php

class dmWidgetGoogleMapSmartGoogleMapForm extends dmWidgetPluginForm
{

  public function configure()
  {
      parent::configure();    
      
      $this->widgetSchema['smartGoogleMap'] = new sfWidgetFormInputHidden(array(),array('value' => 'true'));
      $this->validatorSchema['smartGoogleMap'] = new sfValidatorBoolean(array('required' => false));
      
      $this->widgetSchema['withResume'] = new sfWidgetFormInputCheckbox(array('default' => true));
      $this->validatorSchema['withResume'] = new sfValidatorBoolean(array('required' => false));
      $this->widgetSchema['withResume']->setLabel('Afficher le résumé de l\'implantation');
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