<?php

class vasvcComponents extends myFrontModuleComponents
{

  public function executeForm()
  {
    $this->form = $this->forms['vasvcForm'];
    $this->form->removeCsrfProtection();

  }
  
}