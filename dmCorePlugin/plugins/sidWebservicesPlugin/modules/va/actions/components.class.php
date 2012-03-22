<?php

class vaComponents extends myFrontModuleComponents
{

  public function executeForm()
  {
    $this->form = $this->forms['vaForm'];
    $this->form->removeCsrfProtection();

  }
  
}