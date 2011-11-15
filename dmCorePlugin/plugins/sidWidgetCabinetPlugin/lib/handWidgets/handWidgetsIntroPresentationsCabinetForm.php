<?php

class handWidgetsIntroPresentationsCabinetForm extends dmWidgetPluginForm {

    public function configure() {

        $this->widgetSchema['page'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'SidCabinetAccueil'
                ));
        $this->validatorSchema['page'] = new sfValidatorDoctrineChoice(array(
                    'required' => true,
                    'model' => 'SidCabinetAccueil'
                ));
	
        $this->widgetSchema['lenght'] = new sfWidgetFormInputText(array('default'=>0));
        $this->validatorSchema['lenght'] = new sfValidatorInteger(array(
                    'required' => false
                ));
       $this->widgetSchema['title_page'] = new sfWidgetFormInputText();
        $this->validatorSchema['title_page'] = new sfValidatorString(array(
                    'required' => false
                ));
        
        $this->widgetSchema->setHelps(array(
            'page' => 'Choisir une page du cabinet',
            'lenght' => 'Nombre de caractère du chapeau',
            'title_page'=> 'Titre du bloc'
            
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
        return $this->getHelper()->renderPartial('handWidgets', 'introPresentationsCabinetForm', array(
            'form' => $this,
            'id' => 'sid_widget_intro_presentations_cabinet_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}