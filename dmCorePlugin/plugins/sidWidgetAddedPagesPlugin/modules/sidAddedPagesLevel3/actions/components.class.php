<?php
/**
 * Page de niveau 3 components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 */
class sidAddedPagesLevel3Components extends myFrontModuleComponents
{

  public function executeListBySidAddedPagesLevel2(dmWebRequest $request)
  {
    $query = $this->getListQuery();
    // si on est sur une page de niveau3, on affiche en listing tous les articles du même niveau sauf celui de la page (record_id)
     if($this->context->getPage()->getModule().'/'.$this->context->getPage()->getAction() != 'sidAddedPagesLevel2/show'){
    $query->addWhere('id <> ?', $this->context->getPage()->getRecordId());
    };
    $query->addOrderBy('position');
    
    $this->sidAddedPagesLevel3Pager = $this->getPager($query);
    
    
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
    
    $this->sidAddedPagesLevel3 = $this->getRecord($query);
    
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
