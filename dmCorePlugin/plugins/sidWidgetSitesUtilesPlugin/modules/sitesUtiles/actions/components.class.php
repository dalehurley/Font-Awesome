<?php
/**
 * Le site utile components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 * 
 */
class sitesUtilesComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->sitesUtilesPager = $this->getPager($query);
  }

  public function executeListByGroupeSitesUtiles()
  {
    $query = $this->getListQuery();
    
    $this->sitesUtilesPager = $this->getPager($query);
  }

  public function executeShow()
  {
    $query = $this->getShowQuery();
    
    $this->sitesUtiles = $this->getRecord($query);
  }



}
