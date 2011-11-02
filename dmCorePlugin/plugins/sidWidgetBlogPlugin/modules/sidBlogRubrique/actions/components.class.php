<?php
/**
 * Rubrique de mon Article components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */
class sidBlogRubriqueComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->sidBlogRubriquePager = $this->getPager($query);
  }

  public function executeShow()
  {
    $query = $this->getShowQuery();
    
    $this->sidBlogRubrique = $this->getRecord($query);
  }


}
