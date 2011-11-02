<?php

class handWidgetsArticlesBySectionView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'section',
            'nbArticles',
            'largeur',
            'hauteur'
        ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();

        //$articles = dmDb::table('sid_article')->findBySectionIdAndIsActive($vars['section'],true);

        $articles = dmDb::table('SidArticle') //->findAllBySectionId($vars['section']);
                ->createQuery('a')
                ->where('a.section_id = ?', $vars['section'])
                ->limit($vars['nbArticles']) 
                ->orderBy('filename DESC')
                ->execute();
        
//        $sectionPage = dmDb::table('DmPage') //->findAllBySectionId($vars['section']);
//                ->createQuery('p')
//                ->where('p.module = ? and action=? and record_id=?', array('section','show',$vars['section']))
//                ->limit(1) 
//                ->execute();
//        
//        $rubriquePage = dmDb::table('DmPage') //->findAllBySectionId($vars['section']);
//                ->createQuery('p')
//                ->where('p.module = ? and action=? and record_id=?', array('rubrique','show',$sectionPage[0]->id))
//                ->limit(1) 
//                ->execute();
        
        return $this->getHelper()->renderPartial('handWidgets', 'articlesBySection', array(
            'articles' => $articles,
            'nbArticles' => $vars['nbArticles'],
            'largeur' => $vars['largeur'],
            'hauteur' => $vars['hauteur'],
//            'sectionName' => $sectionPage[0],    // on envoi la première page trouvée   
//            'rubriqueName' => $rubriquePage[0]
        ));
    }

}
