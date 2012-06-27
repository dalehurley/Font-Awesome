<?php
/**
 * Nom du groupe components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 * 
 * 
 */
class sidAddedPagesGroupsComponents extends myFrontModuleComponents
{

  public function executeListPagesLevel1s(dmWebRequest $request)
  {
    $query = $this->getListQuery();
    
    $this->sidAddedPagesGroupsPager = $this->getPager($query);
  }


}
