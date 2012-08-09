<?php

class sfValidatorJsonString extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage('json', 'Not welled formated in JSON.');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {

    if (!json_decode($value))
    {
      throw new sfValidatorError($this, 'json', array());
    }

    return $value;
  }
}