<?php
/**
 * Mon Article components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 * 
 * 
 * 
 * 
 */
class sidActuArticleComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    $this->sidActuArticlePager = $this->getPager($query);
    $this->nbreArticle = count($query);
    $this->pageName = $this->getPage()->getName();
  }

  public function executeShow()
  {
    $query = $this->getShowQuery();
    $this->sidActuArticle = $this->getRecord($query);
    $download = $this->getRecord($query);
  }

  public function executeListParRubrique()
  {
    $query = $this->getListQuery();
    $sidActuArticlePager = $this->getPager($query);
    $this->sidActuArticlePager = $this->getPager($query);
    $this->pageName = $this->getPage()->getName();
  }

  public function executeListParTags()
  {
    $array = array();
      $query = $this->getListQuery();
    $this->sidActuArticlePager = $this->getPager($query);
  }

  public function executeListArticlesAvecMemeTag()
 {
        //    $query = $this->getListQuery();
        //
    //    $this->sidActuArticlePager = $this->getPager($query);
        $arrayArticle = array();
        $arrayIdTags = array();
        $query = $this->getShowQuery();
        $request = $this->getRecord($query);
        $tags = dmDb::pdo(
                        'SELECT * FROM sid_actu_article_dm_tag WHERE id = ' . $request->id
                )->fetchAll();
        foreach ($tags as $dmTagId) {
            $arrayIdTags[] = $dmTagId['dm_tag_id'];
        }
        foreach ($arrayIdTags as $arrayIdTag) {
            // je cherche les articles portant le même tag
            $articleTags = dmDb::pdo(
                            'SELECT * FROM sid_article_dm_tag WHERE dm_tag_id = ' . $arrayIdTag
                    )->fetchAll();
            // Je boucle pour récupérer les articles ayant les mêmes tags
            foreach ($articleTags as $articleTag) {
                $tag = dmDb::table('DmTag')->findOneById($articleTag['dm_tag_id']);
                // Je récupère la collection d'objet ayant le même tag
                $articles = $tag->getRelatedRecords('SidArticle');
                // Je mets en tableau chaque objet article
                foreach ($articles as $article) {
                    if (array_key_exists($article->filename, $arrayArticle) === FALSE) {
                        $arrayArticle[$article->filename] = $article;
                        // je cherche le nom des rubriques et des sections
                        $nomPage['section'] = $article->getSectionPageName();
                        $nomPage['rubrique'] = $article->getRubriquePageName();
                        $nomPages[$article->id] = $nomPage;
                        $nomPage = array();
                    }
                }
            }
        }
        // je trie TOUS les articles de la rubrique par ordre de mise à jour
        foreach ($arrayArticle as $key => $value) {
            $updated[$key] = $value['updated_at'];
        }
        array_multisort($updated, SORT_DESC, $arrayArticle);
        $arrayArticle = array_slice($arrayArticle, 0, 5);
        $this->nomPages = $nomPages;
        $this->articles = $arrayArticle;
    }

  public function executeListMissionsAvecMemeTag()
  {
    //    $query = $this->getListQuery();
    //
    //    $this->sidActuArticlePager = $this->getPager($query);
      $arrayMission = array();
    $arrayIdTags = array();
      $query = $this->getShowQuery();
    $request = $this->getRecord($query);
            $articleTags = dmDb::pdo(
                            'SELECT * FROM sid_actu_article_dm_tag WHERE id = ' .  $request->id
                    )->fetchAll();
    foreach($articleTags as $dmTagId)
        {
            $arrayIdTags[]=$dmTagId['dm_tag_id'];
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
    //    $this->sidActuArticlePager = $this->getPager($query);
      $arrayEquipe = array();
        $arrayIdTags = array();
        $query = $this->getShowQuery();
        $request = $this->getRecord($query);
        $articleTags = dmDb::pdo(
                            'SELECT * FROM sid_actu_article_dm_tag WHERE id = ' .  $request->id
                    )->fetchAll();
        foreach ($articleTags as $dmTagId) {
            $arrayIdTags[] = $dmTagId['dm_tag_id'];
        }
        foreach ($arrayIdTags as $arrayIdTag) {
            // je cherche les membres du cabinet portant le même tag
            // BUG ----- La requète ci-dessous ne fonctionne pas si la table appelée est EGALEMENT EN RELATION AVEC DmMedia ----- BUG
            // $equipeTags = dmDb::table('SidCabinetEquipeDmTag')->findByDmTagId($arrayIdTag);
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

  public function executeListSitesUtilesAvecMemeTag()
  {
    //    $query = $this->getListQuery();
    //
    //    $this->sidActuArticlePager = $this->getPager($query);
      $arraySite = array();
        $arrayIdTags = array();
        $query = $this->getShowQuery();
        $request = $this->getRecord($query);
        $articleTags = dmDb::pdo(
                            'SELECT * FROM sid_actu_article_dm_tag WHERE id = ' . $request->id
                    )->fetchAll();
        foreach ($articleTags as $dmTagId) {
            $arrayIdTags[] = $dmTagId['dm_tag_id'];
        }
        foreach ($arrayIdTags as $arrayIdTag) {
            // je cherche les sites utiles portant le même tag
            // BUG ----- La requète ci-dessous ne fonctionne pas si la table appelée est EGALEMENT EN RELATION AVEC DmMedia ----- BUG
            // $actusTags = dmDb::table('SidActuArticleDmTag')->findByDmTagId($arrayIdTag);
            $siteTags = dmDb::pdo(
                            'SELECT * FROM sid_sites_utiles_dm_tag WHERE dm_tag_id = ' . $arrayIdTag
                    )->fetchAll();
            // Je boucle pour récupérer les sites utiles ayant les mêmes tags
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

  public function executeListAnnonceRecrutement()
  {
    //    $query = $this->getListQuery();
    //
    //    $this->sidActuArticlePager = $this->getPager($query);
      $requeteAnnonce = dmDb::table('SidCabinetRecrutment')->findOneByOnHome(true);
      $this->sidActuArticlePager = $requeteAnnonce;
//      foreach ($requeteAnnonce as $annonce){
//          echo 'toto'.$annonce->getTitle().'<br />';
//      }
  }

}
