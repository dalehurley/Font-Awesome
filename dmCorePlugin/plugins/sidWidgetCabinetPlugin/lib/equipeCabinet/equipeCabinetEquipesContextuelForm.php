<?php

class equipeCabinetEquipesContextuelForm extends dmWidgetPluginForm {

    public function configure() {

        $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText(array('default' => 'Votre conseil'));
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => false
                ));
	
        $this->widgetSchema['titreLien'] = new sfWidgetFormInputText(array('default' => 'Toutes nos missions'));
        $this->validatorSchema['titreLien'] = new sfValidatorString(array(
                    'required' => false
                ));		

        $this->widgetSchema['nb'] = new sfWidgetFormInputText();
        $this->validatorSchema['nb'] = new sfValidatorInteger(array(
                    'min' => 1,
                    'required' => true
                ));
        
        $this->widgetSchema['lenght'] = new sfWidgetFormInputText(array('default' => 0, 'label' => 'Longueur du texte'));
        $this->validatorSchema['lenght'] = new sfValidatorInteger(array(
                    'required' => false
                ));
        
        $this->widgetSchema->setHelps(array(
            'titreBloc' => 'Le titre du bloc.',  
            'titreLien' => "Le libellé du lien vers toute l'équipe.",   	    
            'nb' => 'Le nombre maximum de membres affichés.',            
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
        return $this->getHelper()->renderPartial('equipeCabinet', 'equipesContextuelForm', array(
            'form' => $this,
            'id' => 'sid_widget_equipes_contextuel_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}