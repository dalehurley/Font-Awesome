<?php

class pagesDuCabinetpageCabinetListForm extends dmWidgetPluginForm {


    public function configure() {

        parent::configure();
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
        return $this->getHelper()->renderPartial('pagesDuCabinet', 'pageCabinetListForm', array(
            'form' => $this,
            'id' => 'sid_widget_page cabinet_list_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}