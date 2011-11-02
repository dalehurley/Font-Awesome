<?php

class dmValidatorLinkUrlInternal extends dmValidatorLinkUrl
{ 
    /**
     * see sfValidatorUrl
     */
      const REGEX_URL_FORMAT = '~^
      ((%s):/)?/                              # protocol  (adding versus dmValidatorLinkUrl => optional OR Internal link with / on beginning)
      (
        ([a-z0-9-]+\.)+[a-z]{2,6}             # a domain name
          |                                   #  or
        \d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}    # a IP address
      )
      (:[0-9]+)?                              # a port (optional)
      (/?|/\S+)                               # a /, nothing or a / with something
    $~ix';
      
      
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->setOption('pattern', new sfCallable(array($this, 'generateRegex')));
  }
  
    /**
   * Generates the current validator's regular expression.
   *
   * @return string
   */
  public function generateRegex()
  {
    return sprintf(self::REGEX_URL_FORMAT, implode('|', $this->getOption('protocols')));
  }
}