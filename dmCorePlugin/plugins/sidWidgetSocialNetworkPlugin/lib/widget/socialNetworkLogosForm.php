<?php

class socialNetworkLogosForm extends dmWidgetPluginForm {
    
    protected static $effects = array(
            'slide-hori' => 'slide horizontal', 
            'slide-vert' => 'slide vertical', 
            'fade' => 'fondu', 
            'resize' => 'redimensionnement',             
            'none' => 'aucun'
            );

    public function configure() {

        $this->widgetSchema['twitter'] = new sfWidgetFormInputText(array('default' => 'http://www.twitter.com/xxx'));
        $this->validatorSchema['twitter'] = new sfValidatorString(array('required' => false));

        $this->widgetSchema['facebook'] = new sfWidgetFormInputText(array('default' => 'http://www.facebook.com/xxxx'));
        $this->validatorSchema['facebook'] = new sfValidatorString(array('required' => false));

        $this->widgetSchema['googleplus'] = new sfWidgetFormInputText(array('default' => 'http://www.googleplus.com/xxxx'));
        $this->validatorSchema['googleplus'] = new sfValidatorString(array('required' => false));

  
        
        $this->widgetSchema->setHelps(array(
            'twitter' => 'Le lien vers vers votre compte twitter',
            'facebook' => "Le lien vers vers votre compte facebook",
            'googleplus' => "Le lien vers vers votre compte googleplus"
        ));
        
        parent::configure();
    }

    public function getStylesheets() {
        return array(

        );
    }

    public function getJavascripts() {
        return array(

        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('widget', 'socialNetworkLogosForm', array(
            'form' => $this,
            'id' => 'sid_widget_social_network_logos_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}