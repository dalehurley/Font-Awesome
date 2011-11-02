<?php

class BasedmTagComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();

    $this->dmTagPager = $this->getPager($query);
  }

  public function executePopular()
  {
    $this->dmTags = dmDb::table('DmTag')->getPopularTags(array(), 100);
  }

  public function executeShow()
  {
    $query = $this->getShowQuery();

    $this->dmTag = $this->getRecord($query);
  }

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