<?php
/**
 * Mon Article components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */
class sidBlogArticleComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->sidBlogArticlePager = $this->getPager($query);
    $this->nbreArticle = count($query);
  }

  public function executeShow()
  {
    $query = $this->getShowQuery();
    
    $this->sidBlogArticle = $this->getRecord($query);
$download = $this->getRecord($query);
    
  }

  public function executeListeParRubrique()
  {
    $query = $this->getListQuery();
    
    $this->sidBlogArticlePager = $this->getPager($query);
  }

  public function executeListeParTags()
  {
    $query = $this->getListQuery();
    
    $this->sidBlogArticlePager = $this->getPager($query);
  }


}
