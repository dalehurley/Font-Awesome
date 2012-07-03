<?php
/**
 * Rubrique components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 */
class rubriqueComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->rubriquePager = $this->getPager($query);
  }

  public function executeShow()
  {
    $query = $this->getShowQuery();
    
    $this->rubrique = $this->getRecord($query);
  }

  public function executeListSections(dmWebRequest $request)
  {
    $query = $this->getListQuery();
    
    $this->rubriquePager = $this->getPager($query);
  }


}
