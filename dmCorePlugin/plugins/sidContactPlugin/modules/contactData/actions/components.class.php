<?php
/**
 * Contact components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */
class contactDataComponents extends myFrontModuleComponents
{

  public function executeForm(dmWebRequest $request)
  {
    $this->form = $this->forms['SidContactData'];
    $this->name = $this->getRequest()->getAttribute('name');
    $this->description = $this->getRequest()->getAttribute('description');
    
    if ($this->getRequest()->getAttribute('error')){
    	$this->error = $this->getRequest()->getAttribute('error');	
    } else {
    	$this->error = '';
    }

    if ($this->getRequest()->getAttribute('destinataires')){
      $this->destinataires = $this->getRequest()->getAttribute('destinataires');  
    } else {
      $this->destinataires = '';
    }




  }

}
