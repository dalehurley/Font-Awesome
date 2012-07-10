<?php
/**
 * Page Additionnelle components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 */
class sidAddedPagesComponents extends myFrontModuleComponents
{

  public function executeList(dmWebRequest $request)
  {
    $query = $this->getListQuery();
    // si on est sur une page de niveau3, on affiche en listing tous les articles du même niveau sauf celui de la page (record_id)
     if($this->context->getPage()->getRecordId() <> 0){
    $query->addWhere('id <> ? AND level =?',array($this->context->getPage()->getRecordId(),$this->context->getPage()->getRecord()->level));
    };
    
    $this->sidAddedPagesPager = $this->getPager($query);
    
    // récupération des dimensions des images pour l'affichage
    $theme = array();
    // "renommage du site_theme" pour lecture app
    foreach(sfConfig::get('app_site-theme_rename') as $key=>$nameTheme){
        if($nameTheme == dmConfig::get('site_theme')) {
            // récupération en tableau des dimensions selon le theme du site
            $theme = sfConfig::get('app_added-pages_'.$key);
            break;
        }
    }
    $this->theme = $theme;
  }

  public function executeShow(dmWebRequest $request)
  {
    $query = $this->getShowQuery();
    
    $this->sidAddedPages = $this->getRecord($query);
    
    // récupération des dimensions des images pour l'affichage
    $theme = array();
    // "renommage du site_theme" pour lecture app
    foreach(sfConfig::get('app_site-theme_rename') as $key=>$nameTheme){
        if($nameTheme == dmConfig::get('site_theme')) {
            // récupération en tableau des dimensions selon le theme du site
            $theme = sfConfig::get('app_added-pages_'.$key);
            break;
        }
    }
    $this->theme = $theme;
  }

  public function executeListChildren(dmWebRequest $request)
  {
    $query = $this->getListQuery();
    $query->addWhere('level = ? AND lft > ? AND rgt < ?',array($this->context->getPage()->getRecord()->level+1,$this->context->getPage()->getRecord()->lft, $this->context->getPage()->getRecord()->rgt));   // level n+1
//    $query->andWhere('root_id = ?',$this->context->getPage()->getRecord()->root_id); // même root
    
    $this->sidAddedPagesPager = $this->getPager($query);
    
    // récupération des dimensions des images pour l'affichage
    $theme = array();
    // "renommage du site_theme" pour lecture app
    foreach(sfConfig::get('app_site-theme_rename') as $key=>$nameTheme){
        if($nameTheme == dmConfig::get('site_theme')) {
            // récupération en tableau des dimensions selon le theme du site
            $theme = sfConfig::get('app_added-pages_'.$key);
            break;
        }
    }
    $this->theme = $theme;
  }

  public function executeListRoot(dmWebRequest $request)
  {
    $query = $this->getListQuery();
    $query->addWhere('level = ?',0);
    
    $this->sidAddedPagesPager = $this->getPager($query);
    
    // récupération des dimensions des images pour l'affichage
    $theme = array();
    // "renommage du site_theme" pour lecture app
    foreach(sfConfig::get('app_site-theme_rename') as $key=>$nameTheme){
        if($nameTheme == dmConfig::get('site_theme')) {
            // récupération en tableau des dimensions selon le theme du site
            $theme = sfConfig::get('app_added-pages_'.$key);
            break;
        }
    }
    $this->theme = $theme;
  }


}
