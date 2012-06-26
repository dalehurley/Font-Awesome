<?php

class dmModuleComponent extends dmConfigurable
{
  protected
  $key;

  public function __construct($key, array $options)
  {
    $this->key = $key;

    $this->options = $options;
  }
  
  public function isCachable()
  {
    return $this->getOption('cache', false);
  }

  public function getName()
  {
    return $this->getOption('name');
  }

  public function getType()
  {
    return $this->getOption('type');
  }

/**
 * Return the form class use for form and view
 * Example: if form_class in modules.yml is myClass then dmwidgetTypeManager try to use myClassForm for the module render widget form
 * @return [type] [description]
 */
  public function getFormClass()
  {
    return $this->getOption('form_class');
  }

  public function getKey()
  {
    return $this->key;
  }

  public function getUnderscore()
  {
    return dmString::underscore($this->getKey());
  }

  public function __toString()
  {
    return $this->getKey();
  }
}