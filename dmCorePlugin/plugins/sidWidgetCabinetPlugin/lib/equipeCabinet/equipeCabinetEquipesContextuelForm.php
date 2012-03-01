<?php

class equipeCabinetEquipesContextuelForm extends dmWidgetPluginForm {

    public function configure() {
        
        parent::configure();

        $this->widgetSchema['titreBloc']->setDefault('Votre conseil');
	
        $this->widgetSchema['lien']->setDefault('Toute l\'équipe');
       
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