<?php

class dmWidgetXmlReadWithXslForm extends dmWidgetPluginForm {

    public function configure() {
        $this->widgetSchema['xml'] = new sfWidgetFormInputText();
        $this->validatorSchema['xml'] = new dmValidatorStringEscape(array(
                    'required' => true
                ));

        $this->widgetSchema['xsl'] = new sfWidgetFormInputText();
        $this->validatorSchema['xsl'] = new dmValidatorStringEscape(array(
                    'required' => true
                ));

        $this->widgetSchema->setHelp('xml', 'L\'adresse du fichier XML');
        $this->widgetSchema->setHelp('xsl', 'L\'adresse du fichier XSL');


        parent::configure();
    }

}