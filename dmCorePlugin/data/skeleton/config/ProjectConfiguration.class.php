<?php

require_once ##DIEM_CORE_STARTER##;
dm::start();

class ProjectConfiguration extends dmProjectConfiguration
{

  public function setup()
  {
    parent::setup();
    
    $this->enablePlugins(array(
      // add plugins you want to enable here
    ));

    $this->setWebDir(##DIEM_WEB_DIR##);

    $sessionDir = sfConfig::get('sf_root_dir').'/data/sessions';
    if (!is_dir($sessionDir)) mkdir ($sessionDir); 
    ini_set('session.save_path',$sessionDir);    
  }
  
}