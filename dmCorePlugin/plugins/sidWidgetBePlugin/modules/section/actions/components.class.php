<?php
/**
 * Section components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 */
class sectionComponents extends myFrontModuleComponents
{

  public function executeListByRubrique()
  {
    $query = $this->getListQuery();
    
    $this->sectionPager = $this->getPager($query);
    $this->namePage = $this->getPage()->getName();
  }

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->sectionPager = $this->getPager($query);
  }

  public function executeShow()
  {
    $query = $this->getShowQuery();
    
    $this->section = $this->getRecord($query);
  }

  public function executeMenuDesSectionsDeRubrique()
  {
    $sections = array();
      $requeteSections = dmDb::table('SidSection')->findByIsActive(true);
    foreach($requeteSections as $section){
        if($section->getRubrique() == 'actualites'){
            $sections[] = $section;
        }
    }
    //    echo '<pre>';print_r($sections);echo '</pre>';
    $this->sections = $sections;
  }

  public function executeListArticles(dmWebRequest $request)
  {
    $query = $this->getListQuery();
    $this->sectionPager = $this->getPager($query);
  }


}
