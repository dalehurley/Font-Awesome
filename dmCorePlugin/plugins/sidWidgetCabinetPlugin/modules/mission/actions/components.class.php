<?php
/**
 * Mission components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 * 
 * 
 * 
 */
class missionComponents extends myFrontModuleComponents
{

  public function executeShow()
  {
    $query = $this->getShowQuery();
    
    $this->mission = $this->getRecord($query);
  }

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->missionPager = $this->getPager($query);
  }


  public function executeListArticlesAvecMemeTag()
  {
//        $query = $this->getListQuery('a');
//        $query = $query->addGroupBy('a.filename');
//        $this->articles = $this->getPager($query);
    $arrayArticle = array();
    $arrayIdTags = array();
      $query = $this->getShowQuery();
    $request = $this->getRecord($query);
            $missionTags = dmDb::table('SidCabinetMissionDmTag')->findById($request->id);
    foreach($missionTags as $dmTagId)
        {
            $arrayIdTags[]=$dmTagId->dm_tag_id;
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
                if(array_key_exists($article->id,$arrayArticle) === FALSE){
                $arrayArticle[$article->id] = $article;
                }
            }
        }
        // je trie TOUS les articles de la rubrique par ordre de mise à jour
        foreach ($arrayArticle as $key => $value) {
            $updated[$key] = $value['updated_at'];
        }
        array_multisort($updated, SORT_DESC, $arrayArticle);
        $arrayArticle = array_slice($arrayArticle, 0, 5);
        $this->articles = $arrayArticle;
        }
  }

  public function executeListMembresEquipeAvecMemeTag()
  {
    //    $query = $this->getListQuery();
    //
    //    $this->missionPager = $this->getPager($query);
      $arrayEquipe = array();
        $arrayIdTags = array();
        $query = $this->getShowQuery();
        $request = $this->getRecord($query);
        $articleTags = dmDb::table('SidCabinetMissionDmTag')->findById($request->id);
        foreach ($articleTags as $dmTagId) {
            $arrayIdTags[] = $dmTagId->dm_tag_id;
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

  public function executeListActusCabinetAvecMemeTag()
  {
    //    $query = $this->getListQuery();
    //
    //    $this->missionPager = $this->getPager($query);
      $arrayActu = array();
        $arrayIdTags = array();
        $query = $this->getShowQuery();
        $request = $this->getRecord($query);
        $articleTags = dmDb::table('SidCabinetMissionDmTag')->findById($request->id);
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

  public function executeListParTags()
  {
    $query = $this->getListQuery();
    
    $this->missionPager = $this->getPager($query);
  }

  public function executeListSitesUtilesAvecMemeTag()
  {
//    $query = $this->getListQuery();
//
//    $this->missionPager = $this->getPager($query);
      $arraySite = array();
        $arrayIdTags = array();
        $query = $this->getShowQuery();
        $request = $this->getRecord($query);
        $articleTags = dmDb::table('SidCabinetMissionDmTag')->findById($request->id);
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


}
