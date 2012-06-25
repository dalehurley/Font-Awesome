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

  }


}
