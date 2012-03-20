<?php

class dmWidgetGoogleMapSmartGoogleMapForm extends dmWidgetPluginForm
{

  public function configure()
  {
      parent::configure();    

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