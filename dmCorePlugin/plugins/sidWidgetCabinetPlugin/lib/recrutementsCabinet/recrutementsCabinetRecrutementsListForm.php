<?php


class recrutementsCabinetRecrutementsListForm extends dmWidgetPluginForm {

    public function configure() {

        $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText(array('default' => 'Recrutement'));
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => true
                ));

        $this->widgetSchema['nbRecrutements'] = new sfWidgetFormInputText(array('default' => 0));
        $this->validatorSchema['nbRecrutements'] = new sfValidatorInteger(array(
                    'required' => true
                ));
        
        $this->widgetSchema['longueurTexte'] = new sfWidgetFormInputText(array('default' => 0));
        $this->validatorSchema['longueurTexte'] = new sfValidatorInteger(array(
                    'required' => false
                ));
        
        $this->widgetSchema->setHelps(array(
            'titreBloc' => 'Le titre OBLIGATOIRE du bloc.',  
            'nbRecrutements' => 'Le nombre maximum de recrutements affichés.',            
            'longueurTexte' => 'Longueur du texte avant de la tronquer'
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
            'sidWidgetCabinetPlugin.widgetShowForm'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('recrutementsCabinet', 'recrutementsListForm', array(
            'form' => $this,
            'id' => 'sid_widget_recrutements_list_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}