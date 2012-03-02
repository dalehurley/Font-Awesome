<?php

class specifiquesBaseEditorialeListActualiteForm extends dmWidgetPluginForm {

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
//            'sidWidgetBePlugin.widgetShowForm'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'listActualiteForm', array(
            'form' => $this,
            'id' => 'sid_widget_list_actualite_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}