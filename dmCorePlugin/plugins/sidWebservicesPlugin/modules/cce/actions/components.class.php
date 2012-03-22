<?php

class cceComponents extends myFrontModuleComponents
{

  public function executeForm()
  {
    $this->form = $this->forms['cceForm'];
    $this->form->removeCsrfProtection();

  }
  
}