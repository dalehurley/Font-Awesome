<?php

class trcComponents extends myFrontModuleComponents
{

  public function executeForm()
  {
    $this->form = $this->forms['trcForm'];
    $this->form->removeCsrfProtection();

  }
  
}