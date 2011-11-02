<?php
/**
 * Constantes components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 */
class constantesComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->constantesPager = $this->getPager($query);
  }

  public function executeShow()
  {
    $query = $this->getShowQuery();
    
    $this->constantes = $this->getRecord($query);
  }


}
