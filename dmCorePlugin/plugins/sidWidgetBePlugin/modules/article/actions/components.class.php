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

  public function executeShow() {
        $query = $this->getShowQuery();
        $this->article = $this->getRecord($query);
        $this->route = $this->getPage()->getTitle();
        $ancestors = $this->context->getPage()->getNode()->getAncestors();
        $this->section = $ancestors[count($ancestors)-1]->getTitle();
        $this->rubrique = $ancestors[count($ancestors)-2]->getTitle();
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
