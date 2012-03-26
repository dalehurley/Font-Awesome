<?php

class acsComponents extends myFrontModuleComponents
{

  public function executeForm()
  {
    $this->form = $this->forms['acsForm'];
    $this->form->removeCsrfProtection();

  }
  
}