<?php

class ctrComponents extends myFrontModuleComponents
{

  public function executeForm()
  {
    $this->form = $this->forms['ctrForm'];
    $this->form->removeCsrfProtection();

  }
  
}