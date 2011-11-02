<?php
/**
 * Article components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 */
class articleComponents extends myFrontModuleComponents
{

  public function executeListBySection()
  {
    $query = $this->getListQuery();
    
    $this->articlePager = $this->getPager($query);
    $this->route = $this->getPage()->getTitle();
    $ancestors = $this->context->getPage()->getNode()->getAncestors();
    $this->parent = $ancestors[count($ancestors)-1]->getTitle();
    
    //$this->articlePager->setOption('ajax', true);
  }

  public function executeList()
  {
    $query = $this->getListQuery('a');
    $query = $query->addGroupBy('a.filename');
    $this->articlePager = $this->getPager($query);
    
    $this->articlePager->setOption('ajax', true);
  }

  public function executeShow()
  {
    $query = $this->getShowQuery();
    	$this->article = $this->getRecord($query);
   $ancestors = $this->context->getPage()->getNode()->getAncestors(); 
   $nomSection = $ancestors[count($ancestors)-1]->getName();
   $this->nomSection = $nomSection;
  }

  public function executeListBySectionSlide()
  {
    $query = $this->getListQuery();
    
    $this->articlePager = $this->getPager($query);
    
    $this->articlePager->setOption('ajax', true);
  }

  public function executeListBySectionFeature()
  {
    $query = $this->getListQuery();
    
    $this->articlePager = $this->getPager($query);
    
    $this->articlePager->setOption('ajax', true);
  }

  public function executeListSlide()
  {
    $query = $this->getListQuery();
    
    $this->articlePager = $this->getPager($query);
    
    $this->articlePager->setOption('ajax', true);
  }

  public function executeListFeature()
  {
    $query = $this->getListQuery();
    
    $this->articlePager = $this->getPager($query);
    
    $this->articlePager->setOption('ajax', true);
  }

  public function executeDernierArticleDeChaqueCategorie()
  {
    // initialisation des variables
        $rubriqueActu = '';
        $arrayDernierArticle = array();
        $arrayListArticle = array();
        // je cherche l'id de la rubrique actualité
        $tableRubriques = Doctrine::getTable('SidRubrique')->findByIsActive(true);
        foreach ($tableRubriques as $rubrique) {
            if ($rubrique == 'actualites') {
                $rubriqueActu = $rubrique->id;
                break;
            }
        }
        // je cherche les id des sections de la rubrique ACTUALITES
        $tableSections = Doctrine::getTable('SidSection')->findByRubriqueIdAndIsActive($rubriqueActu, true);
        foreach ($tableSections as $section) {
            // boucle pour les articles expert-comptables
                // je stocke LE dernier article de chaque section (ORDER BY ID DESC)
                $requestArticle = Doctrine_Query::create()->from('SidArticle a')
                                ->where('a.section_id = ?', $section->id)
                                ->orderBy('a.id DESC')
                                ->fetchOne();
                //je mets en tableau temporaire les renseignements (objet de la requète)
                $arrayDernierArticle = array('id'=>$requestArticle->id, 'filename'=>$requestArticle->filename, 'section'=>$section, 'titre'=>$requestArticle);
                //je regroupe tous les tableaux de renseignements dans un tableau global
                $arrayListArticle[] = $arrayDernierArticle;
                // j'efface mon tableau temporaire
                $arrayDernierArticle = array();
        }
         
        $this->listeDernierArticles = $arrayListArticle;
  }

  public function executeListeParTags()
  {
    // initialisation des variables
      $array = array();
        $arrayArticle = array();
        $query = $this->getListQuery();
        
        $articlePager = $this->getPager($query);
     $this->articlePager = $articlePager;
  }

  public function executeListArticlesAvecMemeTag()
  {
    //    $query = $this->getListQuery();
    //    $this->articlePager = $this->getPager($query);
    $query = $this->getShowQuery();
    $array = array();
        // Affichage des articles avec les mêmes tags que celui-ci
        $request = $this->getRecord($query);
        // Je cherche le ou les ids des tags associés à l'article en faisant une requête par L'ID DE L'ARTICLE
        $articleTags = dmDb::table('SidArticleDmTag')->findById($request->id);
     // Je boucle pour récupérer les articles ayant les mêmes tags
        foreach ($articleTags as $articleTag) {
            $tag = dmDb::table('DmTag')->findOneById($articleTag->dm_tag_id);
        // Je récupère la collection d'objet ayant le même tag
            $articles = $tag->getRelatedRecords('SidArticle');
        // Je mets en tableau chaque objet article
            foreach ($articles as $article) {
                if((array_key_exists($article->filename,$array) === FALSE) && ($article->id != $request->id)){
                $array[$article->filename] = $article;
                }
            }
                   foreach ($array as $key => $value) {
            $updated[$key] = $value['updated_at'];
        }
        //array_multisort($updated, SORT_DESC, $array);
        $array = array_slice($array, 0, 5);
        }
        $this->articles = $array;
    // je cherche le nom de la page SECTION et RUBRIQUE de chaque article
        
        if(count($array) != NULL){
    foreach($array as $article){
    $nomPage['section'] = $article->getSectionPageName();
    $nomPage['rubrique'] = $article->getRubriquePageName();
    $nomPages[$article->id] = $nomPage;
    $nomPage = array();
    }
    $this->nomPages = $nomPages;
        }
  }
  

  public function executeListMissionsAvecMemeTag()
  {
    //    $query = $this->getListQuery();
    //
    //    $this->articlePager = $this->getPager($query);
    $arrayMission = array();
    $arrayIdTags = array();
      $query = $this->getShowQuery();
    $request = $this->getRecord($query);
            $articleTags = dmDb::table('SidArticleDmTag')->findById($request->id);
    foreach($articleTags as $dmTagId)
        {
            $arrayIdTags[]=$dmTagId->dm_tag_id;
        }
        foreach ($arrayIdTags as $arrayIdTag) {
        // je cherche les missions portant le même tag
        $missionsTags = dmDb::table('SidCabinetMissionDmTag')->findByDmTagId($arrayIdTag);
        // Je boucle pour récupérer les articles ayant les mêmes tags
        foreach ($missionsTags as $missionsTag) {
            $tag = dmDb::table('DmTag')->findOneById($missionsTag->dm_tag_id);
        // Je récupère la collection d'objet ayant le même tag
            $missions = $tag->getRelatedRecords('SidCabinetMission');
        // Je mets en tableau chaque objet article
            foreach ($missions as $mission) {
                if(array_key_exists($mission->id,$arrayMission) === FALSE){
                $arrayMission[$mission->id] = $mission;
                }
            }
     
        }
        $this->missions = $arrayMission;
        }
  }

  public function executeListMembresEquipeAvecMemeTag()
  {
    //    $query = $this->getListQuery();
        //
        //    $this->articlePager = $this->getPager($query);
        $arrayEquipe = array();
        $arrayIdTags = array();
        $query = $this->getShowQuery();
        $request = $this->getRecord($query);
        $articleTags = dmDb::table('SidArticleDmTag')->findById($request->id);
        foreach ($articleTags as $dmTagId) {
            $arrayIdTags[] = $dmTagId->dm_tag_id;
        }
        foreach ($arrayIdTags as $arrayIdTag) {
            // je cherche les membres du cabinet portant le même tag
            // BUG ----- La requète ci-dessous ne fonctionne pas si la table appelée est EGALEMENT EN RELATION AVEC DmMedia ----- BUG
            // $equipeTags = dmDb::table('SidCabinetEquipeDmTag')->findByDmTagId($arrayIdTag);
            // on effectue une requete brute pour palier le bug
            $equipeTags = dmDb::pdo(
                            'SELECT * FROM sid_cabinet_equipe_dm_tag WHERE dm_tag_id = ' . $arrayIdTag
                    )->fetchAll();
            // Je boucle pour récupérer les articles ayant les mêmes tags
            foreach ($equipeTags as $equipeTag) {
                $tag = dmDb::table('DmTag')->findOneById($equipeTag['dm_tag_id']);
                // Je récupère la collection d'objet ayant le même tag
                $equipes = $tag->getRelatedRecords('SidCabinetEquipe');
                // Je mets en tableau chaque objet article
                foreach ($equipes as $equipe) {
                    if (array_key_exists($equipe->id, $arrayEquipe) === FALSE) {
                        $arrayEquipe[$equipe->id] = $equipe;
                    }
                }
            }
            $this->equipes = $arrayEquipe;
        }
  }

  public function executeListActusCabinetAvecMemeTag()
  {
    //    $query = $this->getListQuery();
    //
    //    $this->articlePager = $this->getPager($query);
        $arrayActu = array();
        $arrayIdTags = array();
        $query = $this->getShowQuery();
        $request = $this->getRecord($query);
        $articleTags = dmDb::table('SidArticleDmTag')->findById($request->id);
        foreach ($articleTags as $dmTagId) {
            $arrayIdTags[] = $dmTagId->dm_tag_id;
        }
        foreach ($arrayIdTags as $arrayIdTag) {
            // je cherche les actus du cabinet portant le même tag
            // BUG ----- La requète ci-dessous ne fonctionne pas si la table appelée est EGALEMENT EN RELATION AVEC DmMedia ----- BUG
            // $actusTags = dmDb::table('SidActuArticleDmTag')->findByDmTagId($arrayIdTag);
            $actusTags = dmDb::pdo(
                            'SELECT * FROM sid_actu_article_dm_tag WHERE dm_tag_id = ' . $arrayIdTag
                    )->fetchAll();
            // Je boucle pour récupérer les articles ayant les mêmes tags
            foreach ($actusTags as $actusTag) {
                $tag = dmDb::table('DmTag')->findOneById($actusTag['dm_tag_id']);
                // Je récupère la collection d'objet ayant le même tag
                $actus = $tag->getRelatedRecords('SidActuArticle');
                // Je mets en tableau chaque objet article
                foreach ($actus as $actu) {
                    if (array_key_exists($actu->id, $arrayActu) === FALSE) {
                        $arrayActu[$actu->id] = $actu;
                    }
                }
            }
            $this->actus = $arrayActu;
        }
  }

  public function executeListSitesUtilesAvecMemeTag()
  {
    //    $query = $this->getListQuery();
    //
    //    $this->articlePager = $this->getPager($query);
      $arraySite = array();
        $arrayIdTags = array();
        $query = $this->getShowQuery();
        $request = $this->getRecord($query);
        $articleTags = dmDb::table('SidArticleDmTag')->findById($request->id);
        foreach ($articleTags as $dmTagId) {
            $arrayIdTags[] = $dmTagId->dm_tag_id;
        }
        foreach ($arrayIdTags as $arrayIdTag) {
            // je cherche les sites utiles portant le même tag
            // BUG ----- La requète ci-dessous ne fonctionne pas si la table appelée est EGALEMENT EN RELATION AVEC DmMedia ----- BUG
            // $actusTags = dmDb::table('SidActuArticleDmTag')->findByDmTagId($arrayIdTag);
            $siteTags = dmDb::pdo(
                            'SELECT * FROM sid_sites_utiles_dm_tag WHERE dm_tag_id = ' . $arrayIdTag
                    )->fetchAll();
            // Je boucle pour récupérer les articles ayant les mêmes tags
            foreach ($siteTags as $siteTag) {
                $tag = dmDb::table('DmTag')->findOneById($siteTag['dm_tag_id']);
                // Je récupère la collection d'objet ayant le même tag
                $sites = $tag->getRelatedRecords('SidSitesUtiles');
                // Je mets en tableau chaque objet article
                foreach ($sites as $site) {
                    if (array_key_exists($site->id, $arraySite) === FALSE) {
                        $arraySite[$site->id] = $site;
                    }
                }
            }
            $this->sites = $arraySite;
        }
  }

  public function executeShowArticleWithPhoto()
  {
    $query = $this->getShowQuery();
    
    $this->article = $this->getRecord($query);
  }

  public function executeShowArticleWithTitle()
  {
    $query = $this->getShowQuery();
    
    $this->article = $this->getRecord($query);
  }


}
