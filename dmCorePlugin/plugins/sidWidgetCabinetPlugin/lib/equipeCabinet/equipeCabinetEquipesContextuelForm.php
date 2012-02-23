<?php

class equipeCabinetEquipesContextuelForm extends dmWidgetPluginForm {

    public function configure() {
        
        parent::configure();

        $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText(array('default' => 'Votre conseil'));
	
        $this->widgetSchema['lien'] = new sfWidgetFormInputText(array('default' => 'Toute l\'équipe'));
       
        $this->validatorSchema['nbArticles'] = new sfValidatorInteger(array(
                    'min' => 1,
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