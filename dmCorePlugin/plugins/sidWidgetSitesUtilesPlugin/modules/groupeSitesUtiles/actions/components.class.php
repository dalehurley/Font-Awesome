<?php
/**
 * Le groupe de sites utiles components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */
class groupeSitesUtilesComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->groupeSitesUtilesPager = $this->getPager($query);
    
   // $this->pageTitle = $this->getPage()->getTitle();
    
  }

  public function executeShow()
  {
    $query = $this->getShowQuery();
    
    $this->groupeSitesUtiles = $this->getRecord($query);
  }


}
