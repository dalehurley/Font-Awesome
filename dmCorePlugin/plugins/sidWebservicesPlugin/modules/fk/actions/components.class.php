<?php

class fkComponents extends myFrontModuleComponents
{

  public function executeForm()
  {
    $this->form = $this->forms['fkForm'];
    $this->form->removeCsrfProtection();

  }
  
}