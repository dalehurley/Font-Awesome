<?php

class pagesDuCabinetIntroPageCabinetForm extends dmWidgetPluginForm {


    public function configure() {

        parent::configure();
        
        $this->widgetSchema['page'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'SidCabinetPageCabinet'
                ));
        $this->validatorSchema['page'] = new sfValidatorDoctrineChoice(array(
                    'required' => true,
                    'model' => 'SidCabinetPageCabinet'
                ));

        $this->widgetSchema['lien']->setDefault('Vers la page du cabinet');
        
        $this->widgetSchema->setHelp('page' ,'Choisir une page du cabinet');
          

        
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
//            'sidWidgetCabinetPlugin.widgetShowForm'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('pagesDuCabinet', 'introPageCabinetForm', array(
            'form' => $this,
            'id' => 'sid_widget_intro_page cabinet_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}