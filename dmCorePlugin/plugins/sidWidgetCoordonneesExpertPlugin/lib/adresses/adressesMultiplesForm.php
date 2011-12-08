<?php

class adressesMultiplesForm extends dmWidgetPluginForm {

    public function configure() {

        $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText();
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => false
                ));

        $this->widgetSchema->setHelps(array(
            'titreBloc' => 'Le titre du bloc.'
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
            'sidWidgetCoordonneesExpertPlugin.widgetShowForm'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('adresses', 'multiplesForm', array(
                    'form' => $this,
                    'id' => 'sid_widget_adresses_multiples' . $this->dmWidget->get('id')
                ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}
