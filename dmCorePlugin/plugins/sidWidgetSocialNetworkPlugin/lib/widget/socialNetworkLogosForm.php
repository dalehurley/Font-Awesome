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
		
        $this->widgetSchema['facebook'] = new sfWidgetFormInputText(array('default' => 'http://www.facebook.com'));
        $this->validatorSchema['facebook'] = new sfValidatorString(array('required' => false));

        $this->widgetSchema['googleplus'] = new sfWidgetFormInputText(array('default' => 'http://www.googleplus.com'));
        $this->validatorSchema['googleplus'] = new sfValidatorString(array('required' => false));

        $this->widgetSchema['linkedin'] = new sfWidgetFormInputText(array('default' => 'http://www.linkedin.com'));
        $this->validatorSchema['linkedin'] = new sfValidatorString(array('required' => false));
		
		$this->widgetSchema['twitter'] = new sfWidgetFormInputText(array('default' => 'http://www.twitter.com'));
        $this->validatorSchema['twitter'] = new sfValidatorString(array('required' => false));
        
        $this->widgetSchema['viadeo'] = new sfWidgetFormInputText(array('default' => 'http://www.viadeo.com'));
        $this->validatorSchema['viadeo'] = new sfValidatorString(array('required' => false));

        $this->widgetSchema['vimeo'] = new sfWidgetFormInputText(array('default' => 'http://www.vimeo.com'));
        $this->validatorSchema['vimeo'] = new sfValidatorString(array('required' => false));

        $this->widgetSchema->setHelps(array(
            'facebook' => "Le lien vers votre compte Facebook",
            'googleplus' => "Le lien vers votre compte Google+",
            'linkedin' => 'Le lien vers votre compte Linkedin',
            'twitter' => 'Le lien vers votre compte Twitter',
            'viadeo' => 'Le lien vers votre compte Viadeo',
            'vimeo' => 'Le lien vers votre compte Vimeo',                        
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