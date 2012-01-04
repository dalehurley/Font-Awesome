<?php

/**
 * PluginSidArticle
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginSidArticle extends BaseSidArticle {

    /**
     *
     * @return string section's page's name of the article
     * 
     */
    public function getSectionPageTitle() {
        $sectionPage = dmDb::table('DmPage')
                ->createQuery('p')
                ->where('p.module = ? and action=? and record_id=?', array('section', 'show', $this->getSection()->id))
                ->limit(1)
                ->execute();
        return $sectionPage[0]->title;
    }
    
     /**
     *
     * @return string rubrique's page's name of the article
     * 
     */
    public function getRubriquePageTitle() {
        $rubriquePage = dmDb::table('DmPage')
                ->createQuery('p')
                ->where('p.module = ? and action=? and record_id=?', array('rubrique', 'show', $this->getSection()->getRubrique()->id))
                ->limit(1)
                ->execute();
        return $rubriquePage[0]->title;
    }

}