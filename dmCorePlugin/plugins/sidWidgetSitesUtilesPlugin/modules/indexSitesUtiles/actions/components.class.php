<?php
/**
 * Index sites utiles components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */
class indexSitesUtilesComponents extends myFrontModuleComponents
{

  public function executeShow()
  {
    $query = $this->getShowQuery();
    
    $this->indexSitesUtiles = $this->getRecord($query);
  }


}
