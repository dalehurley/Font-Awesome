<?php

class dmModuleSpace
{

  protected
    $name,
    $type,
    $modules,
    $slug;
  
  public function initialize($name, dmModuleType $type, array $modules = array())
  {
    $this->name     = $name;
    $this->type     = $type;
    $this->modules  = $modules;
    $this->slug     = null;
  }

  public function getType()
  {
    return $this->type;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getPublicName() 
  {
      // modif stef pour modifier les noms des menus de l'admin en fonction des client_type (ec -aga -cgp) et de la lang du site 
      if (is_object(sfContext::getInstance()->getUser())) {
            if (sfConfig::get('app_traductions-module_' . dmConfig::get('client_type') . '-' . sfContext::getInstance()->getUser()->getCulture())) {
                $arrayTraductionModule = sfConfig::get('app_traductions-module_' . dmConfig::get('client_type') . '-' . sfContext::getInstance()->getUser()->getCulture());
                if (array_key_exists($this->name, $arrayTraductionModule)) {
                    $name = $arrayTraductionModule[$this->name];
                } else {
                    $name = $this->name;
                }
            }
            else
                $name = $this->name;
        }
        else
            $name = $this->name;

        return $name;
      // fin modif
      //return $this->name;
    }

  public function getModules()
  {
    return $this->modules;
  }

  public function hasModules()
  {
    return count($this->modules);
  }

}