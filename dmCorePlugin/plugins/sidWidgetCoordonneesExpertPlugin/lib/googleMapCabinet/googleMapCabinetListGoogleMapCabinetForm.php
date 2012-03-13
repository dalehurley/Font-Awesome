<?php

class googleMapCabinetListGoogleMapCabinetForm extends dmWidgetPluginForm {

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
        return $this->getHelper()->renderPartial('googleMapCabinet', 'listGoogleMapCabinetForm', array(
                    'form' => $this,
                    'id' => 'sid_widget_coordonnees_expert_list_google_map' . $this->dmWidget->get('id')
                ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}
