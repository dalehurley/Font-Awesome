<?php
/**
 * Page du Cabinet components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 */
class pageCabinetComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    //    $query = $this->getListQuery();
    //
    //    $this->pageCabinetPager = $this->getPager($query);
    $pageCabinet = Doctrine_Query::create()->from('SidCabinetPageCabinet a')
                        ->where('a.is_active = ?', true)
                        ->execute();
      $this->pageCabinetList = $pageCabinet;
  }

  public function executeShow()
  {
    $query = $this->getShowQuery();
    
    $this->pageCabinet = $this->getRecord($query);
    $nom = Doctrine_Query::create()->from('SidCoordName a')
                        ->where('a.siege_social = ?', true)
                        ->fetchOne();
        $this->nom = $nom;
        
    $pageCabinet = $this->getRecord($query);
    $replace = array('#Le Nom du Cabinet#', '#le Nom du Cabinet#', '#le nom du cabinet#');
        $text = str_replace($replace, $nom->getTitle(), $pageCabinet->getText());
        $this->pageCabinetText = $text;
  }

}
