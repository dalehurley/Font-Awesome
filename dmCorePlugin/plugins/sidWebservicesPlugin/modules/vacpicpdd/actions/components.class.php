<?php

class vacpicpddComponents extends myFrontModuleComponents
{

  public function executeForm()
  {
    $this->form = $this->forms['vacpicpddForm'];
    $this->form->removeCsrfProtection();

  }
  
}