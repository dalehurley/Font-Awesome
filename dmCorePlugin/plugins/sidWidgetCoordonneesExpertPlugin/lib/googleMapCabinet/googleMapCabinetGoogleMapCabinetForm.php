<?php

class googleMapCabinetGoogleMapCabinetForm extends dmWidgetPluginForm {

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
//            'sidWidgetCoordonneesExpertPlugin.widgetShowForm'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('googleMapCabinet', 'googleMapCabinetForm', array(
                    'form' => $this,
                    'id' => 'sid_widget_coordonnees_expert_google_map' . $this->dmWidget->get('id')
                ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}
