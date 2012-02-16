<?php

class pagesDuCabinetIntroPageCabinetForm extends dmWidgetPluginForm {


    public function configure() {

        $this->widgetSchema['page'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'SidCabinetPageCabinet'
                ));
        $this->validatorSchema['page'] = new sfValidatorDoctrineChoice(array(
                    'required' => true,
                    'model' => 'SidCabinetPageCabinet'
                ));
	
        $this->widgetSchema['length'] = new sfWidgetFormInputText(array('default'=>0));
        $this->validatorSchema['length'] = new sfValidatorInteger(array(
                    'required' => false
                ));
       $this->widgetSchema['title_page'] = new sfWidgetFormInputText();
        $this->validatorSchema['title_page'] = new sfValidatorString(array(
                    'required' => false
                ));
        $this->widgetSchema['lien'] = new sfWidgetFormInputText(array('default'=>'Vers la page du cabinet'));
        $this->validatorSchema['lien'] = new sfValidatorString(array(
                    'required' => false
                ));
        
        $this->widgetSchema->setHelps(array(
            'page' => 'Choisir une page du cabinet',
            'length' => 'Nombre de caractère du chapeau',
            'title_page'=> 'Titre du bloc, si vide on affiche le titre de la page',
            'lien'=> 'Titre du lien pour aller sur la page concernée',
            'withImage' => 'affiche ou pas la photo',
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