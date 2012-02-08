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

// essai avorté, problème d'acès entre apache et user qui installe... On laisse le repertoire de session par défaut
//    $sessionDir = sfConfig::get('sf_root_dir').'/sessions';
//    if (!is_dir($sessionDir)) mkdir ($sessionDir);  => create in installer.php
//    ini_set('session.save_path',$sessionDir);    
  }
  
}