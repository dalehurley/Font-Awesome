<?php

/**
 * PluginSidContactData form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id$
 * @generator  Diem 5.4.0-DEV
 * @gen-file   /data/www/_lib/diem/dmCorePlugin/data/generator/dmDoctrineForm/default/template/sfDoctrineFormPluginTemplate.php */
abstract class PluginSidContactDataForm extends BaseSidContactDataForm
{
  public function setup()
  {
    parent::setup();
    /*
     * Here, the plugin form code
     */
    $this->changeToEmail('email');
    $this->widgetSchema->setHelp('email', 'Your email will never be published');

    if ($this->isCaptchaEnabled()) {
            $this->addCaptcha();
      }
  }

	public function addCaptcha() {
        $this->widgetSchema['captcha'] = new sfWidgetFormReCaptcha(array(
            'public_key' => sfConfig::get('app_sid-recaptcha_public_key')
        ));
        $this->validatorSchema['captcha'] = new sfValidatorReCaptchaDm(array(
            'private_key' => sfConfig::get('app_sid-recaptcha_private_key')
        ));
        $this->widgetSchema->setHelp('captcha', 'Thanks to copy words');
  }
  
  public function isCaptchaEnabled() {
        
        return sfConfig::get('app_sid-recaptcha_enabled');
  }  
}