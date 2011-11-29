<?php

class handWidgetsMissionShowForm extends dmWidgetPluginForm {

    public function configure() {

        $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText();
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => true
                ));
        
        $this->widgetSchema->setHelps(array(
            'titreBloc' => 'Le titre OBLIGATOIRE du bloc.'
        ));

        parent::configure();
    }

    public function getStylesheets() {
        return array(
            'lib.ui-tabs'
        );
    }

    public function getJavascripts() {
        return array(
            'lib.ui-tabs',
            'core.tabForm',
            'sidWidgetBePlugin.widgetShowForm'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('handWidgets', 'missionShowForm', array(
            'form' => $this,
            'id' => 'sid_widget_mission_show_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}