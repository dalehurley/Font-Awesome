<?php

class ctComponents extends myFrontModuleComponents
{

  public function executeForm()
  {
    $this->form = $this->forms['ctForm'];
    $this->form->removeCsrfProtection();

  }
  
}