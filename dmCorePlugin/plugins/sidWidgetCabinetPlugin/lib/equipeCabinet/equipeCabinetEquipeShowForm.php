<?php

class equipeCabinetEquipeShowForm extends dmWidgetPluginForm {

    public function configure() {

        parent::configure();
                
        $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText(array('default' => 'Notre équipe, vos conseils'));
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => true
                ));

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
        return $this->getHelper()->renderPartial('equipeCabinet', 'equipeShowForm', array(
                    'form' => $this,
                    'id' => 'sid_widget_equipe_show_' . $this->dmWidget->get('id')
                ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}