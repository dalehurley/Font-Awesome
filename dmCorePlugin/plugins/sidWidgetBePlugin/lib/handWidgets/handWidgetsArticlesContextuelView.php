<?php

class handWidgetsArticlesContextuelView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'm_rubriques_list_1',
            'titreLien_1',
            'm_rubriques_list_2',
            'titreLien_2',
            'm_rubriques_list_3',
            'titreLien_3',
            'longueurTexte',
            'photo',
        ));
    }

    /**
     * On affiche NB articles Actu selon 3 types:
     * 1) il est sur une dmPage automatique de type "Rubrique" : on affiche les articles qui sont dans les sections de cette rubrique
     * 2) il est sur une dmPage automatique de type "Section" : on affiche les articles qui sont dans cette section
     * 3) il est sur une page ni Rubrique ni Section, automatique ou pas : on affiche les articles de la rubrique choisie par défaut
     *  
     */
    protected function doRender() {
        $vars = $this->getViewVars();
        $arrayArticle = array();
        $arrayRubriquesLists = array();
        $arrayTitreLiens = array();
        $arrayLienIds = array();

        // Mise en tableau des $vars['m_rubriques_list_]
        array_push($arrayRubriquesLists, $vars['m_rubriques_list_1'], $vars['m_rubriques_list_2'], $vars['m_rubriques_list_3']);
        // Mise en tableau des $vars['titreLien_]
        array_push($arrayTitreLiens, $vars['titreLien_1'], $vars['titreLien_2'], $vars['titreLien_3']);

        $idDmPage = sfContext::getInstance()->getPage()->id;
        $dmPage = dmDb::table('DmPage')->findOneById($idDmPage);
        switch ($dmPage->module . '/' . $dmPage->action) {

            case 'article/show':
                break;
            case 'article/list':
                break;

            default:

                // dans la page d'accueil, on renvoie le dernièr article des rubriques choisies
                foreach ($arrayRubriquesLists as $i => $rubriqueList) {
                    if ($rubriqueList != null) {
                        $articles = Doctrine_Query::create()->from('SidArticle sa')
                                ->Where('sa.is_active = ? and sa.section_id = ?', array(true, $rubriqueList))
                                ->orderBy('sa.updated_at DESC')
                                ->limit(1)
                                ->execute();

                        foreach ($articles as $article) { // on stocke les NB actu article 
                            $arrayArticle[$article->id] = $article;
                            $arrayLienIds[$article->id] = $arrayTitreLiens[$i];
                        }
                    }
                }
        }

        return $this->getHelper()->renderPartial('handWidgets', 'articlesContextuel', array(
                    'articles' => $arrayArticle,
                    'lien' => $arrayLienIds,
                    'titreBloc' => $vars['titreBloc'],
                    'longueurTexte' => $vars['longueurTexte'],
                    'photo' => $vars['photo']
                ));
    }

}
