<?php

class specifiquesBaseEditorialeFilActualiteView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'lien',
            'length',
            'nbArticles',
            'section',
            'withImage',
            'widthImage'
        ));
    }
	
	public function getStylesheets() {
		//on créé un nouveau tableau car c'est un nouveau widget (si c'est une extension utiliser $stylesheets = parent::getStylesheets();)
		$stylesheets = array();
		
		//lien vers le js associé au menu
		$cssLink = '/theme/css/_templates/'.dmConfig::get('site_theme').'/Widgets/SpecifiquesBaseEditorialeFilActualite/SpecifiquesBaseEditorialeFilActualite.css';
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


        $idDmPage = sfContext::getInstance()->getPage()->id;
        $dmPage = dmDb::table('DmPage')->findOneById($idDmPage);

        switch($dmPage->module.'/'.$dmPage->action){

            case 'article/show':
                $arrayFilActus = array();
                break;
            
            default :
            $listSectionId = implode(",", $vars['section']);
            // requete brute
            $req = '    SELECT s.id FROM sid_article s LEFT JOIN sid_article_translation s2 
                        ON s.id = s2.id 
                        WHERE s.is_active = true
                        and s.section_id in ('.$listSectionId.')
                        group by s.filename
                        ORDER BY s2.updated_at DESC
                        LIMIT '.$vars['nbArticles'];

            $articleIds = dmDb::pdo($req, array(), dmDb::table('SidArticle')->getConnection())->fetchAll(PDO::FETCH_COLUMN);

            foreach ($articleIds as $articleId) {
                $arrayFilActus[] = dmDb::table('SidArticle')->find($articleId);
            }
        }

        ($vars['lien'] != NULL || $vars['lien'] != " ") ? $lien = $vars['lien'] : $lien = '';
        return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'filActualite', array(
                    'articles' => $arrayFilActus,
                    'titreBloc' => $vars['titreBloc'],
                    'lien' => $lien,
                    'length' => $vars['length'],
                    'section' => $vars['section'],
                    'withImage' => $vars['withImage'],
                    'widthImage' => $vars['widthImage'],
                    'justTitle' => isset($vars['justTitle'])?$vars['justTitle']:false
                ));
    }

}
