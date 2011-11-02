<?php

class handWidgetsListAgendaForm extends dmWidgetPluginForm {

    public function configure() {

        $this->widgetSchema['nbArticles'] = new sfWidgetFormInputText();
        $this->validatorSchema['nbArticles'] = new sfValidatorInteger(array(
                    'max' => 4,
                    'min' => 1,
                    'required' => true
                ));
        $this->widgetSchema['title'] = new sfWidgetFormInputText();
        $this->validatorSchema['title'] = new sfValidatorString(array('required' => false));
        
       
        
        $this->widgetSchema->setHelps(array(
            'nbArticles' => 'entre 1 et 4',
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
        return $this->getHelper()->renderPartial('handWidgets', 'listAgendaForm', array(
            'form' => $this,
            'id' => 'sid_widget_list_agenda_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}