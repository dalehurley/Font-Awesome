<?php

class cjoComponents extends myFrontModuleComponents
{

  public function executeForm()
  {
    $this->form = $this->forms['cjoForm'];
    $this->form->removeCsrfProtection();

  }
  
}