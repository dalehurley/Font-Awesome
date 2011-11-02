<?php
/**
 * Mon groupe de bandeaux components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 */
class groupeBandeauComponents extends myFrontModuleComponents
{

  public function executeShow()
  {
    $query = $this->getShowQuery();
    
    $this->groupeBandeau = $this->getRecord($query);
  }


}
