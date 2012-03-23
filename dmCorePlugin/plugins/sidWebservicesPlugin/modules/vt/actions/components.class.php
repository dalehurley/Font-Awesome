<?php

class vtComponents extends myFrontModuleComponents
{

  public function executeForm()
  {
    $this->form = $this->forms['vtForm'];
    $this->form->removeCsrfProtection();

  }
  
}