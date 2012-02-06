<?php

class dmWidgetContentBlocDePubEnFlashForm extends dmWidgetPluginForm {

    public function configure() {
        $this->widgetSchema['pubsId'] = new sfWidgetFormDoctrineChoice(array(
                    'model'=>'DmMediaFolder',
                    'method'=>'getName',
                    'label'=> 'Répertoire des pubs'
                    ));
    $this->validatorSchema['pubsId'] = new sfValidatorDoctrineChoice(array(
                    'model' => 'DmMediaFolder',
                    'required'=>true,
                    ));
    $this->widgetSchema['width'] = new sfWidgetFormInputText(array('default' => 250)) ;
    $this->validatorSchema['width'] = new sfValidatorInteger(array('required' => true));
    
    $this->widgetSchema['height'] = new sfWidgetFormInputText(array('default' => 250)) ;
    $this->validatorSchema['height'] = new sfValidatorInteger(array('required' => true));
    
        $this->widgetSchema->setHelps(array(
            'pubsId' => 'Choisir le répertoire dans lequel se trouvent les pubs', 
            'width' => 'Choisir la largeur de la pub', 
            'height' => 'Choisir la largeur de la pub', 
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
//            'sidWidgetPubsFlashPlugin.widgetShowForm'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('dmWidgetContentBlocDePubEnFlash', 'blocDePubsEnFlashForm', array(
            'form' => $this,
            'id' => 'sid_widget_pubs_flash_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}