<?php

class pagesDuCabinetPageCabinetForm extends dmWidgetPluginForm {

    public function configure() {
       
        parent::configure();
        
        //$this->widgetSchema['lien']->setDefault('Contactez nous');

        
    }

    public function getStylesheets() {
        return array(
//            'lib.ui-tabs'
        );
    }

    public function getJavascripts() {
        return array(
//            'lib.ui-tabs',
//            'core.tabForm',
//            'sidWidgetCabinetPlugin.widgetShowForm'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('pagesDuCabinet', 'pageCabinetForm', array(
            'form' => $this,
            'id' => 'sid_widget_page cabinet_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}