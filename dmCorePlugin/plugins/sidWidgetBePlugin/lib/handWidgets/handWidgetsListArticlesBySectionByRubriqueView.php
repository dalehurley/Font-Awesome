<?php

class handWidgetsListArticlesBySectionByRubriqueView extends dmWidgetPluginView {

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
        
        // Je personnalise le titre du widget
        if($vars['title'] == ''){
            $title = $sectionPage[0]->name;
            
        }
        else $title = $vars['title'];
        
        return $this->getHelper()->renderPartial('handWidgets', 'listArticlesBySectionByRubrique', array(
            'articles' => $articles,
            'nbArticles' => $vars['nbArticles'],
            'photoArticle' => $vars['photo'],
            'titreBloc' => $vars['titreBloc'],
            'link' => $sectionPage,
            'sectionName' => $title,    // on envoi la première page trouvée         
        ));
    }

}
