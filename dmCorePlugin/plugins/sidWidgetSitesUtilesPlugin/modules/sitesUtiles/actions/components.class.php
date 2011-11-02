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

  public function executeListParTags()
  {
    $query = $this->getListQuery();
    
    $this->sitesUtilesPager = $this->getPager($query);
  }

  public function executeMiseEnAvantSitesUtiles() {
        $requeteSites = Doctrine_Query::create()->from('SidSitesUtiles a')
                ->where('a.on_home = ?', true)
                ->andWhere('a.is_active = ?', true)
                ->orderBy('a.updated_at DESC')
                ->execute();
        $this->sites = $requeteSites;
    }


}
