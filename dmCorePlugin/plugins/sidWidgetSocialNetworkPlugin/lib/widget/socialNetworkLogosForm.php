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
		
        $this->widgetSchema['facebook'] = new sfWidgetFormInputText(array('default' => 'http://www.facebook.com/xxxx'));
        $this->validatorSchema['facebook'] = new sfValidatorString(array('required' => false));

        $this->widgetSchema['googleplus'] = new sfWidgetFormInputText(array('default' => 'http://www.googleplus.com/xxxx'));
        $this->validatorSchema['googleplus'] = new sfValidatorString(array('required' => false));

        $this->widgetSchema['linkedin'] = new sfWidgetFormInputText(array('default' => 'http://www.linkedin.com/xxxx'));
        $this->validatorSchema['linkedin'] = new sfValidatorString(array('required' => false));
		
		$this->widgetSchema['twitter'] = new sfWidgetFormInputText(array('default' => 'http://www.twitter.com/xxx'));
        $this->validatorSchema['twitter'] = new sfValidatorString(array('required' => false));
        
        $this->widgetSchema->setHelps(array(
            'facebook' => "Le lien vers vers votre compte Facebook",
            'googleplus' => "Le lien vers vers votre compte Google+",
            'linkedin' => 'Le lien vers vers votre compte Linkedin',
            'twitter' => 'Le lien vers vers votre compte Twitter',
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