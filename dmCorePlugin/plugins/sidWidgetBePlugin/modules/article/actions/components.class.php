<?php
/**
 * Article components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 */
class articleComponents extends myFrontModuleComponents
{

  public function executeListBySection()
  {
    $query = $this->getListQuery('a');
    // si une section contient au moins un dossier alors on ne va cherhcer dans la query que les dossiers de cette section
    // récupération des données pour filtrage des dossiers
    $section_id = $this->getPage()->getRecordId();
    $articleDossier = dmDb::table('SidArticle')->findByIsDossierAndSectionId(true,$section_id);
    if(count($articleDossier)>0){
        $query->addWhere('is_dossier = true');
    }
    if($this->getPage()->getRecord()->getRubrique()->getTitle() == 'ec_echeancier'){
        $query->orderBy('aTranslation.created_at ASC');
    };
    
    // construction du header pour envoyer DIRECTEMENT sur la page si il n'y a q'un article
    $articlePager = $this->getPager($query);
    if(count($articlePager) == 1){
        foreach($articlePager as $article){
            $page = dmDb::table('DmPage')->findOneByModuleAndActionAndRecordId('article','show', $article->id );

            // add current's controler for header() redirection
            $controlers = json_decode(dmConfig::get('base_urls'),true); // all controlers Url
            $contextEnv = sfConfig::get('dm_context_type').'-'.sfConfig::get('sf_environment'); // i.e. "front-dev"
            $controlerUrl = (array_key_exists($contextEnv,$controlers))?$controlers[$contextEnv]:''; 
            $header = $controlerUrl.'/'.$page->getSlug();
            $this->header = $header;
            $this->articlePager = $this->getPager($query);
        }
        
    }
    else{
        $this->articlePager = $this->getPager($query);
        $this->route = $this->getPage()->getTitle();
        $ancestors = $this->context->getPage()->getNode()->getAncestors();
        $this->parent = $ancestors[count($ancestors)-1]->getTitle();
    }
    
    //$this->articlePager->setOption('ajax', true);
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
    $nomPage['section'] = $article->getSectionPageTitle();
    $nomPage['rubrique'] = $article->getRubriquePageTitle();
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
