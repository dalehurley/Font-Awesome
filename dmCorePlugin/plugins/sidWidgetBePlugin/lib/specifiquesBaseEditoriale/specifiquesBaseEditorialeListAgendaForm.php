<?php

class specifiquesBaseEditorialeListAgendaForm extends dmWidgetPluginForm {

    public function configure() {

        $this->widgetSchema['nbArticles'] = new sfWidgetFormInputText(array('default' => 2));
        $this->validatorSchema['nbArticles'] = new sfValidatorInteger(array(
                    'max' => 4,
                    'min' => 1,
                    'required' => true
                ));
        $this->widgetSchema['title'] = new sfWidgetFormInputText(array('default' => 'Agenda'));
        $this->validatorSchema['title'] = new sfValidatorString(array('required' => true));
        
        $this->widgetSchema['lien'] = new sfWidgetFormInputText(array('default' => 'Echéances du mois'));
        $this->validatorSchema['lien'] = new sfValidatorString(array('required' => true));
        
        $this->widgetSchema['length'] = new sfWidgetFormInputText(array('default' => 0));
        $this->validatorSchema['length'] = new sfValidatorInteger();
        
        $this->widgetSchema['pageCentrale']       = new sfWidgetFormInputCheckbox(array('default'=> false));
        $this->validatorSchema['pageCentrale']    = new sfValidatorBoolean();
        
        $this->widgetSchema->setHelps(array(
            'nbArticles' => 'entre 1 et 4',
            'title' => 'Personnaliser le titre du widget',
            'lien' => 'Lien vers la page list du mois en cours',
            'pageCentrale' => 'Pour affichage en "sous-home" de l\'echéancier'
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
        return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'listAgendaForm', array(
            'form' => $this,
            'id' => 'sid_widget_list_agenda_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}