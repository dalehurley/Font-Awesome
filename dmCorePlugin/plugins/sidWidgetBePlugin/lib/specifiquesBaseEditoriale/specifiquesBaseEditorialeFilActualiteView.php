<?php

class specifiquesBaseEditorialeFilActualiteView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'titreLien',
            'longueurTexte',
            'nbArticle',
            'section',
            'photo'
        ));
    }
	
	public function getStylesheets() {
		//on créé un nouveau tableau car c'est un nouveau widget (si c'est une extension utiliser $stylesheets = parent::getStylesheets();)
		$stylesheets = array();
		
		//lien vers le js associé au menu
		$cssLink = sidSPLessCss::getCssPathTemplate(). '/Widgets/SpecifiquesBaseEditorialeFilActualite/SpecifiquesBaseEditorialeFilActualite.css';
		//chargement de la CSS si existante
		if (is_file(sfConfig::get('sf_web_dir') . $cssLink)) $stylesheets[] = $cssLink;
		
		return $stylesheets;
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
        $arrayFilActus = array();
        $arrayTitreLiens = array();
        $arrayLienIds = array();
        $arrayRubrique = array();

        $idDmPage = sfContext::getInstance()->getPage()->id;
        $dmPage = dmDb::table('DmPage')->findOneById($idDmPage);
        switch($dmPage->module.'/'.$dmPage->action){
            
            case 'article/show':
                $arrayFilActus = array();
                break;
            
            default :
        // je récupère les ids des articles appartenants aux sections choisies
        foreach ($vars['section'] as $section) {
            // je récupère UN article (le plus récemment mis à jour) de chaque section
            $articles = dmDb::table('SidArticle')
                    ->createQuery('a')
                    ->leftJoin('a.Translation b')
                    ->where('a.section_id = ? AND a.is_active = ?', array($section, true))
                    ->orderBy('b.updated_at DESC')
                    ->limit(1)
                    ->execute();
            // Pour cet article, je le mets dans un tableau général et je mets dans un tableau le nom de la page de la Rubrique
            foreach ($articles as $article) {

                $rubriquePage = dmDb::table('DmPage') //->findAllBySectionId($vars['section']);
                        ->createQuery('p')
                        ->where('p.module = ? and p.action=? and p.record_id=?', array('rubrique', 'show', $article->Section->rubrique_id))
                        ->limit(1)
                        ->execute();
                
                $arrayRubrique[$article->filename] = $rubriquePage[0]->name;
                $arrayFilActus[$article->filename] = $article;
            }
        }

        foreach ($arrayFilActus as $key => $value) {
            $updated[$key] = $value['updatedAt'];
        }
        array_multisort($updated, SORT_DESC, $arrayFilActus);
        $arrayFilActus = array_slice($arrayFilActus, 0, $vars['nbArticle']);
        }
        
//        foreach ($arrayFilActus as $key => $value) {
//            $updated[$key] = $value['updated_at'];
//        }
//        array_multisort($updated, SORT_DESC, $arrayFilActus);
//        $arrayFilActus = array_slice($arrayFilActus, 0, $vars['nbArticle']);

        return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'filActualite', array(
                    'articles' => $arrayFilActus,
                    'titreBloc' => $vars['titreBloc'],
                    'titreLien' => $vars['titreLien'],
                    'longueurTexte' => $vars['longueurTexte'],
                    'section' => $vars['section'],
                    'arrayRubrique' => $arrayRubrique,
                    'photo' => $vars['photo']
                ));
    }

}
