<?php

class handWidgetsEquipesContextuelForm extends dmWidgetPluginForm {

    public function configure() {

        $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText();
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => false
                ));
	
        $this->widgetSchema['titreLien'] = new sfWidgetFormInputText();
        $this->validatorSchema['titreLien'] = new sfValidatorString(array(
                    'required' => false
                ));		

        $this->widgetSchema['nb'] = new sfWidgetFormInputText();
        $this->validatorSchema['nb'] = new sfValidatorInteger(array(
                    'min' => 1,
                    'required' => true
                ));
        
        $this->widgetSchema->setHelps(array(
            'titreBloc' => 'Le titre optionnel du bloc.',  
            'titreLien' => "Le libellé du lien vers toute l'équipe.",   	    
            'nb' => 'Le nombre maximum de membres affichés.',            
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
        return $this->getHelper()->renderPartial('handWidgets', 'equipesContextuelForm', array(
            'form' => $this,
            'id' => 'sid_widget_equipes_contextuel_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}