<?php

class srtbComponents extends myFrontModuleComponents
{

  public function executeForm()
  {
    $this->form = $this->forms['srtbForm'];
    $this->form->removeCsrfProtection();

  }
  
}