<?php

class cdComponents extends myFrontModuleComponents
{

  public function executeForm()
  {
    $this->form = $this->forms['cdForm'];
    $this->form->removeCsrfProtection();

  }
  
}