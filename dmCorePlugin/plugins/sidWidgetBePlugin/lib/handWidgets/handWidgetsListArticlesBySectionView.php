<?php

class handWidgetsListArticlesBySectionView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'section',
            'nbArticles',
            'title',
            'photo',
            'titreBloc'
        ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();

        //$articles = dmDb::table('sid_article')->findBySectionIdAndIsActive($vars['section'],true);

        $articles = dmDb::table('SidArticle') //->findAllBySectionId($vars['section']);
                ->createQuery('a')
                ->where('a.section_id = ?', $vars['section'])
                ->limit($vars['nbArticles']) 
                ->orderBy('updated_at DESC')
                ->execute();
        
        $sectionPage = dmDb::table('DmPage') //->findAllBySectionId($vars['section']);
                ->createQuery('p')
                ->where('p.module = ? and action=? and record_id=?', array('section','show',$vars['section']))
                ->limit(1) 
                ->execute();
        $rubriqueId = dmDb::table('SidSection')->findOneById($vars['section']);
        
        $rubriquePage = dmDb::table('DmPage')
                ->createQuery('p')
                ->where('p.module = ? and action=? and record_id=?', array('rubrique','show',$rubriqueId->rubrique_id))
                ->limit(1) 
                ->execute();
        
        $rubrique = $rubriquePage[0]->name;
        // Je personnalise le titre du widget
        if($vars['title'] == ''){
            $title = $sectionPage[0]->name;
            
            
        }
        else $title = $vars['title'];
        
        return $this->getHelper()->renderPartial('handWidgets', 'listArticlesBySection', array(
            'articles' => $articles,
            'nbArticles' => $vars['nbArticles'],
            'photoArticle' => $vars['photo'],
            'titreBloc' => $vars['titreBloc'],
            'rubrique' => $rubrique,
            'link' => $sectionPage,
            'sectionName' => $title,    // on envoi la première page trouvée         
        ));
    }

}
