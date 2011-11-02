<?php

class handWidgetsListArticlesByRubriqueView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'rubrique',
            'nbArticles',
            'title',
            'photo',
            'titreBloc'
        ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();
        $arrayArticle = array();
        // Je récupère le nom de la rubrique
//        $rubrique = dmDb::table('SidRubrique')->findOneById($vars['rubrique']);
        $rubrique = dmDb::table('DmPage') //->findAllBySectionId($vars['section']);
                ->createQuery('p')
                ->where('p.module = ? and action=? and record_id=?', array('rubrique', 'show', $vars['rubrique']))
                ->limit(1)
                ->execute();
        
        // je recherche toutes les section de la rubrique choisie
        $sections = dmDb::table('SidSection') //->findAllBySectionId($vars['section']);
                ->createQuery('a')
                ->where('a.rubrique_id = ? and a.is_active = ?', array($vars['rubrique'], true))
                ->orderBy('a.id DESC')
                ->execute();
        // je recherche tous les articles de chaque section que je mets dans un tableau $arrayArticle
        foreach ($sections as $section) {
            $articles = dmDb::table('SidArticle') //->findAllBySectionId($vars['section']);
                    ->createQuery('a')
                    ->where('a.section_id = ? and a.is_active = ?', array($section->id, true))
                    ->orderBy('a.updated_at DESC')
                    ->limit(5)
                    ->execute();

            foreach ($articles as $article) {
                $arrayArticle[] = $article;
            }
        }
        // je trie TOUS les articles de la rubrique par ordre de mise à jour
        foreach ($arrayArticle as $key => $value) {
            $updated[$key] = $value['updated_at'];
        }
        array_multisort($updated, SORT_DESC, $arrayArticle);
        $arrayArticle = array_slice($arrayArticle, 0, $vars['nbArticles']);
        
        // Je personnalise le titre du widget
        if($vars['title'] == ''){
            $title = $rubrique[0]->title;
            
        }
        else $title = $vars['title'];
        
        return $this->getHelper()->renderPartial('handWidgets', 'listArticlesByRubrique', array(
                    'articles' => $arrayArticle,
                    'nbArticles' => $vars['nbArticles'],
                    'rubriqueTitle' => $title, // on envoi la première page trouvée   
                    'rubrique' => '/'.$rubrique[0]->slug,
                    'photoArticle' => $vars['photo'],
                    'titreBloc' => $vars['titreBloc']
                    
                ));
    }

}
