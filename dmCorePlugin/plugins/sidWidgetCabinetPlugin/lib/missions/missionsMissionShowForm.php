<?php

class missionsMissionShowForm extends dmWidgetPluginForm {

    public function configure() {

        $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText(array('default' => 'Nos missions'));
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => true
                ));
        
        $this->widgetSchema->setHelps(array(
            'titreBloc' => 'Le titre du bloc.'
        ));

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
        return $this->getHelper()->renderPartial('missions', 'missionShowForm', array(
            'form' => $this,
            'id' => 'sid_widget_mission_show_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}