<?php

class sidAccueilComponents extends dmAdminBaseComponents 
{
  
  public function executeLittle()
  {
    $this->accueilKey = $this->name;
    $test = $this->getService('module_manager')->getModule($this->name)->getPlural();
    $this->link = $test;
  }
  
  public function executeLarge()
  {
    $this->accueilMessage = $this->name;
    $html = $this->getService('admin_menu')->build()->render();
    $this->html = $html;
  }
  
 
  
}