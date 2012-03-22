<?php

class trsvcComponents extends myFrontModuleComponents
{

  public function executeForm()
  {
    $this->form = $this->forms['trsvcForm'];
    $this->form->removeCsrfProtection();

  }
  
}