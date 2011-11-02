<?php

class DmSigninFrontForm extends DmSigninBaseForm
{
  /**
   * @see sfForm
   */
  public function configure()
  {

    $this->setWidgets(array(
      'username' => new sfWidgetFormInputText(),
      'password' => new sfWidgetFormInputPassword(),
      'remember' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'username' => new sfValidatorString(),
      'password' => new sfValidatorString(),
      'remember' => new sfValidatorBoolean(),
    ));

    $this->setName('signin');

    $this->validatorSchema->setPostValidator(new dmValidatorUser());

  }
}
