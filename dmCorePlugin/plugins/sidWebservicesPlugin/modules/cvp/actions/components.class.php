<?php

class cvpComponents extends myFrontModuleComponents
{

  public function executeForm()
  {
    $this->form = $this->forms['cvpForm'];
    $this->form->removeCsrfProtection();

  }
  
}