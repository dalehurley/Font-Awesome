<?php

class specifiquesBaseEditorialeDessinSemaineForm extends dmWidgetPluginForm {

    public function configure() {

        $this->widgetSchema['title'] = new sfWidgetFormInputText(array('default' => 'Dessin de la semaine'));
        $this->validatorSchema['title'] = new sfValidatorString(array('required' => true));

        
        $this->widgetSchema->setHelps(array(
            'title' => 'Personnaliser le titre du widget',
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
        return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'dessinSemaineForm', array(
            'form' => $this,
            'id' => 'sid_widget_dessin_semaine_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}