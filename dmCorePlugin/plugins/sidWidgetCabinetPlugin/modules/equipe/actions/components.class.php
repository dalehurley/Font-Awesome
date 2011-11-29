<?php
/**
 * Equipe components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 */
class equipeComponents extends myFrontModuleComponents
{

  public function executeList(dmWebRequest $request)
  {
    $query = $this->getListQuery();
    
    $this->equipePager = $this->getPager($query);
  }


}
