<?php

/*
 * @package    symfony
 * @subpackage validator
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfValidatorReCaptcha.class.php 7903 2008-03-15 13:17:41Z fabien $
 */
class sfValidatorReCaptchaDm extends sfValidatorReCaptcha
{
  /**
   * @see sfValidatorReCaptcha
   */
  protected function configure($options = array(), $messages = array())
  {

    parent::configure();
    $this->addMessage('captcha', 'The captcha is not valid');
    $this->addMessage('server_problem', 'Unable to check the captcha from the server');
  }


}
