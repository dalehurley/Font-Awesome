<?php

class handWidgetsArticlesByRubriqueMultipleView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'rubrique',
            'nbArticles'
        ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();
        $arrayArticle = array();
        $arraySections = array();
       
        foreach ($vars['rubrique'] as $idRubrique) {
            $sections = dmDb::table('SidSection')
                    ->createQuery('a')
                    ->where('a.rubrique_id = ?', array($idRubrique))
                    ->execute();
           
            foreach ($sections as $section) {
                $arraySections[] = $section;
            }
        }

        // je recherche tous les articles de chaque section que je mets dans un tableau $arrayArticle
        foreach ($arraySections as $section) {
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
       
        return $this->getHelper()->renderPartial('handWidgets', 'articlesByRubriqueMultiple', array(
                    'articles' => $arrayArticle,
                    'nbArticles' => $vars['nbArticles'],
                    'titreBloc' => $vars['titreBloc']
                ));
    }

}
