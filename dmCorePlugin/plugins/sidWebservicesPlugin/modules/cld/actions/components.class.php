<?php

class cldComponents extends myFrontModuleComponents
{

  public function executeForm()
  {
    $this->form = $this->forms['cldForm'];
    $this->form->removeCsrfProtection();

  }
  
}