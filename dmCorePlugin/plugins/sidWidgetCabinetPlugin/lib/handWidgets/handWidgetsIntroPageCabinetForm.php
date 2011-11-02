<?php

class handWidgetsIntroPageCabinetForm extends dmWidgetPluginForm {

    public function configure() {

        $this->widgetSchema['page'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'SidCabinetPageCabinet'
                ));
        $this->validatorSchema['page'] = new sfValidatorDoctrineChoice(array(
                    'required' => true,
                    'model' => 'SidCabinetPageCabinet'
                ));
	
        $this->widgetSchema['lenght'] = new sfWidgetFormInputText();
        $this->validatorSchema['lenght'] = new sfValidatorInteger(array(
                    'required' => false
                ));
       
        
        $this->widgetSchema->setHelps(array(
            'page' => 'Choisir une page du cabinet',
            'lenght' => 'Nombre de caractère du chapeau'
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
        return $this->getHelper()->renderPartial('handWidgets', 'introPageCabinetForm', array(
            'form' => $this,
            'id' => 'sid_widget_intro_page cabinet_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}