<?php
/**
 * Accueil components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 * 
 * 
 */
class accueilComponents extends myFrontModuleComponents
{

  public function executeShow()
  {
    // Pour récupérer le nom du cabinet principal, région expert-comptable et région commissaires aux comptes
        
        
        $query = $this->getShowQuery();
        $accueil = $this->getRecord($query);
  }

  public function executeShowPresentation()
  {
    $query = $this->getShowQuery();
    
    $accueil = $this->getRecord($query);
    $nom = Doctrine_Query::create()->from('SidCoordName a')
                        ->where('a.siege_social = ?', true)
                        ->fetchOne();
    $this->nom = $nom;
    $replace = array('#Le Nom du Cabinet#', '#le Nom du Cabinet#', '#le nom du cabinet#','#le Nom du cabinet#', '#le nom du Cabinet#' );
        $presentation = str_replace($replace, $nom->getTitle(), $accueil->getPresentation());
        $this->presentation = $presentation;
  }

  public function executeShowMission()
  {
    $query = $this->getShowQuery();
    
    $accueil = $this->getRecord($query);
    $nom = Doctrine_Query::create()->from('SidCoordName a')
                        ->where('a.siege_social = ?', true)
                        ->fetchOne();
    $replace = array('#Le Nom du Cabinet#', '#le Nom du Cabinet#', '#le nom du cabinet#','#le Nom du cabinet#', '#le nom du Cabinet#' );
    $mission = str_replace($replace, $nom->getTitle(), $accueil->getMission());
        $this->mission = $mission;
  }

  public function executeShowEquipe()
  {
    $query = $this->getShowQuery();
    
    $accueil = $this->getRecord($query);
    $nom = Doctrine_Query::create()->from('SidCoordName a')
                        ->where('a.siege_social = ?', true)
                        ->fetchOne();
    $replace = array('#Le Nom du Cabinet#', '#le Nom du Cabinet#', '#le nom du cabinet#','#le Nom du cabinet#', '#le nom du Cabinet#' );
    $equipe = str_replace($replace, $nom->getTitle(), $accueil->getEquipe());
        $this->equipe = $equipe;
  }

  public function executeShowBureauPrincipal()
  {
    //    $query = $this->getShowQuery();
    //
    //    $this->accueil = $this->getRecord($query);
    $nom = Doctrine_Query::create()->from('SidCoordName a')
                        ->where('a.siege_social = ?', true)
                        ->fetchOne();
        $this->nom = $nom;
  }

  public function executeShowBureauSecondaire()
  {
    //    $query = $this->getShowQuery();
    //
    //    $this->accueil = $this->getRecord($query);
      $nomSecondaires = Doctrine_Query::create()->from('SidCoordName a')
                        ->where('a.siege_social = ?', false)
                        ->execute();
        $this->nomSecondaires = $nomSecondaires;
  }

  public function executeShowEntete()
  {
    //    $query = $this->getShowQuery();
    //
    //    $this->accueil = $this->getRecord($query);
      $nom = Doctrine_Query::create()->from('SidCoordName a')
                        ->where('a.siege_social = ?', true)
                        ->fetchOne();
        $this->nom = $nom;
  }

}
