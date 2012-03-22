<?php

class hsComponents extends myFrontModuleComponents
{

  public function executeForm()
  {
    $this->form = $this->forms['hsForm'];
    $this->form->removeCsrfProtection();

  }
  
}