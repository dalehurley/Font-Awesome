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

    // recuperer l'id du widget qui appelle le component
  	$this->test = 'ttt';


  	

    $this->form = $this->forms['SidContactData'];

  }


}
