<?php
/**
 * Equipe components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 * 
 * 
 * 
 * 
 */
class equipeComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    $this->equipePager = $this->getPager($query);
//    $equipePager = Doctrine_Query::create()->from('SidCabinetEquipe a')
//                      ->where('a.is_active = ?', true)
//                      ->execute();
//    $this->equipes = $equipePager;
  }

  public function executeListParTags()
  {
    $query = $this->getListQuery();
    
    $this->equipePager = $this->getPager($query);
  }

  public function executeBlocPhotosEquipe()
  {
    $requetePhoto = dmDb::table('SidCabinetEquipe')->findByIsActive(true);
    $this->photos = $requetePhoto;
  }
}
