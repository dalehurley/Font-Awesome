<?php

class specifiquesBaseEditorialeArticlesBySectionContextuelView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'lien',
            'section',
            'length',
            'nbArticles',
            'isDossier',
            'visibleInDossier'
        ));
    }
	
	public function getStylesheets() {
		//on créé un nouveau tableau car c'est un nouveau widget (si c'est une extension utiliser $stylesheets = parent::getStylesheets();)
		$stylesheets = array();
		
		//lien vers le js associé au menu
		$cssLink = '/theme/css/_templates/'.dmConfig::get('site_theme').'/Widgets/SpecifiquesBaseEditorialeArticlesBySectionContextuel/SpecifiquesBaseEditorialeArticlesBySectionContextuel.css';
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
        $arrayArticle = array();
        $articles = array();
        $sectionNamePage = "";
        $nbArticles = ($vars['nbArticles'] == 0) ? '' : $vars["nbArticles"];
        $idDmPage = sfContext::getInstance()->getPage()->id;
        $dmPage = dmDb::table('DmPage')->findOneById($idDmPage);
        switch ($dmPage->module . '/' . $dmPage->action) {
            case 'rubrique/show':

                $rubrique = dmDb::table('SidRubrique')->findOneById($dmPage->record_id);
                // je scrute les sections choisies pour afficher le contenu dans le bloc
                foreach ($vars['section'] as $section) {
                    // je récupère l'objet Section via l'integer $section qui est l'id de la Section
                    $sectionPages = Doctrine_Query::create()
                            ->from('SidSection sa')
                            ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'sa')
                            ->Where('sa.is_active = ? and sa.id = ?', array(true, $section))
                            ->orderBy('saTranslation.updated_at DESC')
                            ->limit(1)
                            ->execute();
                    // je vérifie que le champ de la section->rubrique_id est le même que le record_id de la page en cours
                    if ($sectionPages[0]->rubrique_id == $rubrique->id) {
                        // traitement pour article, chiffre, faq, aides, paie
                        if($vars['isDossier'] == false){
                        $articles = Doctrine_Query::create()
                                ->from('SidArticle sa')
                                ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'sa')
                                ->Where('sa.is_active = ? and sa.section_id = ? and sa.is_dossier = ?', array(true, $sectionPages[0]->id ,false))
                                ->orderBy('saTranslation.updated_at DESC')
                                ->limit($nbArticles)
                                ->execute();
                        
                        }
                        // traitement pour dossier
                        elseif($vars['isDossier'] == true){
                            $articles = Doctrine_Query::create()
                                ->from('SidArticle sa')
                                ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'sa')
                                ->Where('sa.is_active = ? and sa.section_id = ? and sa.is_dossier = ?', array(true, $sectionPages[0]->id, true))
                                ->orderBy('saTranslation.updated_at DESC')
                                ->limit($nbArticles)
                                ->execute();
                        }
                    }
                    else
                        $articles = array();
                }

                $rubriqueName = dmDb::table('dmPage')->findOneByModuleAndActionAndRecordId($dmPage->module, $dmPage->action, $dmPage->record_id);
//                $sectionName = dmDb::table('dmPage')->findOneByModuleAndActionAndRecordId('section', $dmPage->action, $sectionPages[0]->id);

                break;


            case 'section/show':

                $sectionId = dmDb::table('SidSection')->findOneById($dmPage->record_id);
                $rubrique = dmDb::table('SidRubrique')->findOneById($sectionId->rubrique_id);
                                                
                // je scrute les sections choisies pour afficher le contenu dans le bloc
                foreach ($vars['section'] as $section) {
                    // je récupère l'objet Section via l'integer $section qui est l'id de la Section
                    $sectionPages = Doctrine_Query::create()
                            ->from('SidSection sa')
                            ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'sa')
                            ->Where('sa.is_active = ? and sa.id = ?', array(true, $section))
                            ->orderBy('saTranslation.updated_at DESC')
                            ->limit(1)
                            ->execute();
                    // je vérifie que le champ de la section->rubrique_id est le même que le record_id de la page en cours
                    if ($sectionPages[0]->rubrique_id == $rubrique->id) {
                        // traitement pour article, chiffre, faq, aides, paie
                        if($vars['isDossier'] == false){
                        $articles = Doctrine_Query::create()
                                ->from('SidArticle sa')
                                ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'sa')
                                ->Where('sa.is_active = ? and sa.section_id = ? and sa.is_dossier = ?', array(true, $section,false))
                                ->orderBy('saTranslation.updated_at DESC')
                                ->limit($nbArticles)
                                ->execute();
                    }
                    // traitement pour dossier
                        elseif($vars['isDossier'] == true){   
                            $articles = Doctrine_Query::create()
                                ->from('SidArticle sa')
                                ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'sa')
                                ->Where('sa.is_active = ? and sa.section_id = ? and sa.is_dossier = ?', array(true, $section,true))
                                ->orderBy('saTranslation.updated_at DESC')
                                ->limit($nbArticles)
                                ->execute();
                        } 

//                        $rubriqueName = dmDb::table('dmPage')->findOneByModuleAndActionAndRecordId('rubrique', 'show', $rubrique->id);
//                        $sectionName = dmDb::table('dmPage')->findOneByModuleAndActionAndRecordId($dmPage->module, $dmPage->action, $sectionPages[0]->id);
                    }
                    else
                        $articles = array();
                }
                $rubriqueName = dmDb::table('dmPage')->findOneByModuleAndActionAndRecordId('rubrique', 'show', $rubrique->id);
//                $sectionName = dmDb::table('dmPage')->findOneByModuleAndActionAndRecordId($dmPage->module, $dmPage->action, $sectionPages[0]->id);
                break;

            case 'article/show':

                $articleId = dmDb::table('SidArticle')->findOneById($dmPage->record_id);
                $rubrique = dmDb::table('SidRubrique')->findOneById($articleId->Section->rubrique_id);
                
                // si on est sur la page dossier (via le nom du cookies session articleDataType) et que visibleInDossier n'est pas coché, on n'affiche pas le bloc
                if(((sfContext::getInstance()->getUser()->getAttribute('articleDataType') == sfConfig::get('app_article-data-type-dossier')) && ($vars['visibleInDossier'] == false) && ($vars['isDossier'] == true))
                ||
                // si on est sur la page article (via le nom du cookies session articleDataType) et que visibleInDossier est coché, on n'affiche pas le bloc
                ((sfContext::getInstance()->getUser()->getAttribute('articleDataType') == sfConfig::get('app_article-data-type-article')) && ($vars['visibleInDossier'] == true))){
                    $articles = array();
                    $rubriqueName = "";
                    $sectionName = "";
                }
                // si on est sur la page dossier (via le nom du cookies session articleDataType) et que visibleInDossier est coché, on affiche le bloc
                else if(((sfContext::getInstance()->getUser()->getAttribute('articleDataType') == sfConfig::get('app_article-data-type-dossier')) && ($vars['visibleInDossier'] == true))
                ||
                // si on est sur la page article (via le nom du cookies session articleDataType) et que isDossier est coché, on n'affiche le bloc
                ((sfContext::getInstance()->getUser()->getAttribute('articleDataType') == sfConfig::get('app_article-data-type-article')) && ($vars['isDossier'] == true))){
                
                    // je scrute les sections choisies pour afficher le contenu dans le bloc
                    foreach ($vars['section'] as $section) {
                        // je récupère l'objet Section via l'integer $section qui est l'id de la Section
                        $sectionPages = Doctrine_Query::create()
                                ->from('SidSection sa')
                                ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'sa')
                                ->Where('sa.is_active = ? and sa.id = ?', array(true, $section))
                                ->orderBy('saTranslation.updated_at DESC')
                                ->limit(1)
                                ->execute();

                        if ($sectionPages[0]->rubrique_id == $rubrique->id) {
                            // je vérifie que le champ de la section->rubrique_id est le même que le record_id de la page en cours
                            if($vars['isDossier'] == false){
                            $articles = Doctrine_Query::create()
                                    ->from('SidArticle sa')
                                    ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'sa')
                                    ->Where('sa.is_active = ? and sa.section_id = ? and sa.is_dossier = ?', array(true, $section,false))
                                    ->orderBy('saTranslation.updated_at DESC')
                                    ->limit($nbArticles)
                                    ->execute();
                            }
                            // traitement pour dossier
                            elseif($vars['isDossier'] == true){ 
                                $articles = Doctrine_Query::create()
                                    ->from('SidArticle sa')
                                    ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'sa')
                                    ->Where('sa.is_active = ? and sa.section_id = ? and sa.is_dossier = ?', array(true, $section,true))
                                    ->orderBy('saTranslation.updated_at DESC')
                                    ->limit($nbArticles)
                                    ->execute();
                            }
                        }

    //                    else
    //                        $articles = array();
                    }
                }
                $rubriqueName = dmDb::table('dmPage')->findOneByModuleAndActionAndRecordId('rubrique', 'show', $rubrique->id);
//                $sectionName = dmDb::table('dmPage')->findOneByModuleAndActionAndRecordId('section', $dmPage->action, $articleId->section_id);
                break;

            default :
                $articles = array();
                $rubriqueName = "";
                $sectionName = "";
                break;
        }

        if(count($articles) >0){
        $sectionNamePage = dmDb::table('DmPage')->findOneByModuleAndActionAndRecordId('section','show',$articles[0]->Section->id);
        }
        ($vars['lien'] != NULL || $vars['lien'] != " ") ? $lien = $vars['lien'] : $lien = '';
        return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'articlesBySectionContextuel', array(
                    'articles' => $articles,
                    'titreBloc' => $vars['titreBloc'],
                    'lien' => $lien,
                    'length' => $vars['length'],
                    'rubrique' => $rubriqueName,
                    'section' => $sectionNamePage
                ));
    }

}
