<?php

class handWidgetsActuArticlesListView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'nbArticles',
            'longueurTexte',
            'photo',
            'chapo'
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
        
        if($vars['nbArticles'] == 0) { $nb = dmDb::table('SidActuArticle')->count();}
        else $nb = $vars['nbArticles'];

        $actuArticles = Doctrine_Query::create()
                ->from('SidActuArticle a')
                ->leftJoin('a.SidActuTypeArticle sata')
                ->where('a.is_active = ? and sata.sid_actu_type_id = ?', array(true,$vars['type']))
                ->orderBy('a.updated_at DESC')
                ->limit($nb)
                ->execute();
        
        foreach ($actuArticles as $actuArticle) { // on stock les NB actu article 
                    $arrayArticle[$actuArticle->id] = $actuArticle;
                }

        return $this->getHelper()->renderPartial('handWidgets', 'actuArticlesList', array(
                    'articles' => $arrayArticle,
                    'nbArticles' => $vars['nbArticles'],
                    'titreBloc' => $vars['titreBloc'],
                    'longueurTexte' => $vars['longueurTexte'],
                    'photo' => $vars['photo'],
                    'chapo' => $vars['chapo'],
            
                ));
    }

}
