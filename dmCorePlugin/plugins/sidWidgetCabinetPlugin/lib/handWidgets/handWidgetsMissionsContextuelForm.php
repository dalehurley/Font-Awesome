<?php

class handWidgetsMissionsContextuelForm extends dmWidgetPluginForm {

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
            'titreLien' => 'Le libellé du lien vers toutes les missions.',    	    
            'nb' => 'Le nombre maximum de missions affichées.',            
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
        return $this->getHelper()->renderPartial('handWidgets', 'missionsContextuelForm', array(
            'form' => $this,
            'id' => 'sid_widget_missions_contextuel_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}