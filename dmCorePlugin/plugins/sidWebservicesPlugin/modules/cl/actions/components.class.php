<?php

class clComponents extends myFrontModuleComponents
{

  public function executeForm()
  {
    $this->form = $this->forms['clForm'];
    $this->form->removeCsrfProtection();

  }
  
}