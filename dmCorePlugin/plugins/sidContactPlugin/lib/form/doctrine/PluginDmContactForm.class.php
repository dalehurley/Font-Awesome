<?php
/**
 * PluginDmContact form.
 *
 * This form uses the sfWidgetFormReCaptcha widget
 *
 * The ReCaptcha API documentation can be found at http://recaptcha.net/apidocs/captcha/
 *
 * To be able to use this widget, you need an API key: http://recaptcha.net/api/getkey
 *
 * As it's not possible to change the name of ReCaptcha fields, you will have to add them manually
 * when binding a form from an HTTP request.
 *
 * Here's a typical usage when embedding a captcha in a form with a contact[%s] name format:
 *
 *    $captcha = array(
 *      'recaptcha_challenge_field' => $request->getParameter('recaptcha_challenge_field'),
 *      'recaptcha_response_field'  => $request->getParameter('recaptcha_response_field'),
 *    );
 *    $this->form->bind(array_merge($request->getParameter('contact'), array('captcha' => $captcha)));
 */
abstract 
class PluginDmContactForm extends BaseDmContactForm {
    public function setup() {
        parent::setup();

        $this->changeToEmail('email');
        $this->widgetSchema->setHelp('email', 'Your email will never be published');
        $this->widgetSchema['body']->setLabel('Message');   
        $this->widgetSchema['name']->setLabel('Name');
        $this->widgetSchema['adresse']->setLabel('Adresse');
        $this->widgetSchema['fax']->setLabel('Fax');
        $this->widgetSchema['title']->setLabel('Citizen title');
        $this->widgetSchema['firstname']->setLabel('Firstname');
        $this->widgetSchema['function']->setLabel('Society function');
        $this->widgetSchema['ville']->setLabel('Town');        
        $this->widgetSchema['postalcode']->setLabel('Postal code');
        $this->widgetSchema['phone']->setLabel('Phone number');

        $this->validatorSchema['email']->setOption('required', true);
        $this->validatorSchema['body']->setOption('required', true)->setMessage('required', 'Please enter a message');
        $this->validatorSchema['name']->setOption('required', true);
        $this->validatorSchema['adresse']->setOption('required', false);
        $this->validatorSchema['fax']->setOption('required', false);   
        $this->validatorSchema['firstname']->setOption('required', false);
        $this->validatorSchema['function']->setOption('required', false);
        $this->validatorSchema['ville']->setOption('required', false);        
        $this->validatorSchema['postalcode']->setOption('required', false);
        $this->validatorSchema['phone']->setOption('required', false);

  
        $titles = array(
            'Monsieur' => 'Monsieur',
            'Madame' => 'Madame',
            'Mademoiselle' => 'Mademoiselle'
        );
        
        $this->widgetSchema['title'] = new sfWidgetFormSelect(array(
            'choices' => array('' => 'Choose') + $titles
        ));
        $this->validatorSchema['title'] = new sfValidatorChoice(array(
            'choices' => array_keys($titles),
            'required' => true),
             array(   
            'required' => 'Choose civility',
        ));
        if ($this->isCaptchaEnabled()) {
            $this->addCaptcha();
        }

    }
    public function addCaptcha() {
        $this->widgetSchema['captcha'] = new sfWidgetFormReCaptcha(array(
            'public_key' => sfConfig::get('app_recaptcha_public_key')
        ));
        $this->validatorSchema['captcha'] = new sfValidatorReCaptchaDm(array(
            'private_key' => sfConfig::get('app_recaptcha_private_key')
        ));
        $this->widgetSchema->setHelp('captcha', 'Thanks to copy words');
    }
    public function isCaptchaEnabled() {
        
        return sfConfig::get('app_recaptcha_enabled');
    }
    
}
