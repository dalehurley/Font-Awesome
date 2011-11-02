<?php
/**
 * Rubrique de mon Article components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */
class sidActuRubriqueComponents extends myFrontModuleComponents
{

  public function executeList() {
        $arrayActus = array();
        $query = $this->getListQuery();
        $sidActuRubriquePager = $this->getPager($query);
        $this->sidActuRubriquePager = $this->getPager($query);
        $this->pageName = $this->getPage()->getName();
        foreach ($sidActuRubriquePager as $rubrique) {
            $actus = Dmdb::table('SidActuArticle')->findOneByRubriqueId($rubrique->id);
            if (!empty($actus)) {
                $arrayActus[$rubrique->id] = $actus;
            }
        }
        $this->actuRubriques = $arrayActus;
    }

  public function executeShow()
  {
    $query = $this->getShowQuery();
    
    $this->sidActuRubrique = $this->getRecord($query);
  }


}
