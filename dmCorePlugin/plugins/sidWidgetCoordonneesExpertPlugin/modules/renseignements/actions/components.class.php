<?php
/**
 * Renseignements components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */
class renseignementsComponents extends myFrontModuleComponents
{

  public function executeShow()
  {
    $query = $this->getShowQuery();
    
    $this->renseignements = $this->getRecord($query);
  }


}
