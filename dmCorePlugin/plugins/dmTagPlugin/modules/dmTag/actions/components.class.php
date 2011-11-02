<?php

require_once realpath(dirname(__FILE__).'/..').'/lib/BasedmTagComponents.class.php';

/**
 * Dm tag components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */
class dmTagComponents extends BasedmTagComponents
{

    //      public function executeShow()
//  {
//        $query = $this->getShowQuery();
//
//    $dmTag = $this->getRecord($query);
//    $arrayArticle = array();
//    $arrayGlobal = array();
//          $request = Doctrine::getTable('SidArticleDmTag')->findByDmTagId($dmTag->id);
//          foreach ($request as $idArticle)
//          {
//              $article = Doctrine::getTable('SidArticle')->findByIdAndIsActive($idArticle, true);
//              $arrayArticle['filename'] = $article->getFilename();
//              $arrayArticle['sectionId'] = $article->getSectionId();
//              $arrayArticle['title'] = $article->getTitle();
//              $arrayArticle['chapeau'] = $article->getChapeau();
//              $arrayGlobal[] = $arrayArticle;
//              $arrayArticle = array();
//          }
//          $this->Articles = $arrayGlobal;
//
//  }
}
