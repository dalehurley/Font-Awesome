<?php

/**
 * PluginSidArticleTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginSidArticleTable extends myDoctrineTable {

    /**
     * Returns an instance of this class.
     *
     * @return object PluginSidArticleTable
     */
    public static function getInstance() {
        return Doctrine_Core::getTable('PluginSidArticle');
    }

    /*
     * Returns articles objects.
     *
     * @return object dmDoctrine of articles
     */

    public function getArticlesByRubrique($rubrique) {

        $q = $this->createQuery('c')
                ->leftJoin('c.SidRubrique.Translation rt')
                ->where('rt.title = ? and c.is_active = ?', array($rubrique, true));

        return $q->execute();
    }

    /* pas besoin de filtrer par langue, la langue est setable et getable par l'objet article
      public function getAllArticlesByRubrique($rubrique, $lang ='fr') {

      $q = $this->createQuery('c')
      ->leftJoin('c.SidRubrique.Translation rt')
      ->leftJoin('c.Translation at')
      ->where('rt.title = ? and at.lang = ?', array($rubrique, $lang));

      return $q->execute();
      }
     */

    public function getAllArticlesByRubrique($rubrique) {

        $q = $this->createQuery('a')
                ->leftJoin('a.Section s')
                ->leftJoin('s.Rubrique.Translation rt')
                ->where('rt.title = ? ', $rubrique);

        return $q->getSqlQuery();

        return $q->execute();
    }

    public function allArticle() {
        $a = $this->createQuery('j');
        return $a->execute();
    }

    public function isArticle($filename) {
        $b = $this->createQuery('j')
                ->where('j.filename = ?', $filename);

        return $b->execute()->count();
    }

    public function getArticleByFilename($filename) {

        $q = $this->createQuery('c')
                ->where('filename = ? ', $filename);

        return $q->fetchOne();
    }

    public function findOneByFilenameAndSectionId($filename, $sectionId) {
        $a = $this->createQuery('a')
                ->where('filename = ? and section_id = ?', array($filename, $sectionId));  

        //if ($filename == '110193') return $a->getSqlQuery().$filename.'-'.$sectionId.'->'.$a->execute()->count();  
        
        
        if ($a->execute()->count() < 1) {
            $article = new SidArticle();
            return $article;
        } else {
            return $a->fetchOne(); 
        }
    }
    
    public function getOneByFilename($filename) {
        $a = $this->createQuery('a')
                ->where('filename like ? ', array($filename));  

        if ($a->execute()->count() < 1) {
            $article = new SidArticle();
            return $article;
        } else {
            return $a->fetchOne();
        }
    }
    
    public function getMaxUpdatedAtBySection($sectionId) {
        $a = $this->createQuery('a')
                ->where('section_id = ?', array( $sectionId))
                ->orderBy('updated_at DESC');;  

      //  return $a->getSqlQuery().$sectionId;
       if ($a->execute()->count() < 1) {
            return 0;
        } else {
            return $a->fetchOne()->updatedAt;
        }

    }
    
    

}