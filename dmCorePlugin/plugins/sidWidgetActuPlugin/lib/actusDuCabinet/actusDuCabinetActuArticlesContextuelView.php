<?php

class actusDuCabinetActuArticlesContextuelView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'lien',
            'nbArticles',
            'length',
            'chapo',
            'widthImage',
            'heightImage',
            'withImage',
            'type'
        ));

    }
	
	public function getStylesheets() {
		//on créé un nouveau tableau car c'est un nouveau widget (si c'est une extension utiliser $stylesheets = parent::getStylesheets();)
		$stylesheets = array();
		
		//lien vers le js associé au menu
		$cssLink = '/theme/css/_templates/'.dmConfig::get('site_theme').'/Widgets/ActusDuCabinetActuArticlesContextuel/ActusDuCabinetActuArticlesContextuel.css';
		//chargement de la CSS si existante
		if (is_file(sfConfig::get('sf_web_dir') . $cssLink)) $stylesheets[] = $cssLink;
		
		return $stylesheets;
	}

    protected function doRender() {
        $vars = $this->getViewVars();
        $arrayArticle = array();

        $dmPage = sfContext::getInstance()->getPage();
        //si nbArticle est à 0, on ne mets rien dans le LIMIT pour tout afficher
        $nbArticles = ($vars['nbArticles'] == 0) ? '' : $vars["nbArticles"];
        switch ($dmPage->module . '/' . $dmPage->action) {

//            case 'pageCabinet/equipe':
//                break;

            case 'section/show':
                // il faut que je récupère l'id de la rubrique de la section
                // je récupère donc l'ancestor de la page courante pour extraire le record_id de ce dernier afin de retrouver la rubrique
                $ancestors = $dmPage->getNode()->getAncestors();
                $recordId = $ancestors[count($ancestors) - 1]->getRecordId();
                $actuArticles = Doctrine_Query::create()->from('SidActuArticle a')
                        ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                        ->leftJoin('a.SidActuArticleSidRubrique sas')
                        ->leftJoin('sas.SidRubrique s')
                        ->leftJoin('a.SidActuTypeArticle sata')
                        ->where('s.id = ?  ', array($recordId))
                        ->andWhere('a.is_active = ? and sata.sid_actu_type_id = ? and (aTranslation.debut_date <= ? or aTranslation.debut_date is ?) and (aTranslation.fin_date >= ? or aTranslation.fin_date is ?)', array(true,$vars['type'],date('Y-m-d'),NULL,date('Y-m-d'),NULL))
                        ->orderBy('aTranslation.updated_at DESC')
                        ->limit($nbArticles)
                        ->execute();
                // Si il n'y a pas d'actus associées, on affiche la dernière actu

                if (count($actuArticles) == 0) {
                    
                    
                    $actuArticles = '';
                    $actuArticles = Doctrine_Query::create()->from('SidActuArticle a')
                            ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                            ->leftJoin('a.SidActuTypeArticle sata')
                            ->Where('a.is_active = ? and sata.sid_actu_type_id = ? and (aTranslation.debut_date <= ? or aTranslation.debut_date is ?) and (aTranslation.fin_date >= ? or aTranslation.fin_date is ?)', array(true,$vars['type'], date('Y-m-d'),NULL,date('Y-m-d'),NULL))
                            ->orderBy('aTranslation.updated_at DESC')
                            ->limit($nbArticles)
                            ->execute();
                }
                if(count($actuArticles)){
                    foreach ($actuArticles as $actuArticle) { // on stock les NB actu article 
                        $arrayArticle[$actuArticle->id] = $actuArticle;
                    };
                }
                break;
            case 'rubrique/show':
                // toutes les sections de la rubrique contextuelle
                $rubriques = dmDb::table('SidRubrique')->findById($dmPage->record_id);
                // on parcourt les sections pour extraire les articles
                foreach ($rubriques as $rubrique) {
                    $actuArticles = Doctrine_Query::create()->from('SidActuArticle a')
                            ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                            ->leftJoin('a.SidActuArticleSidRubrique sas')
                            ->leftJoin('sas.SidRubrique s')
                            ->leftJoin('a.SidActuTypeArticle sata')
                            ->where('s.id = ? ', array($rubrique->id))
                            ->andWhere('a.is_active = ?', true)
                            ->andWhere('sata.sid_actu_type_id = ?', array($vars['type']))
                            ->orderBy('aTranslation.updated_at DESC')
                            ->limit($nbArticles)
                            ->execute();

                    // Si il n'y a pas d'actus associées, on affiche la dernière actu

                    if (count($actuArticles) == 0) {
                        $actuArticles = '';
                        $actuArticles = Doctrine_Query::create()->from('SidActuArticle a')
                                ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                                ->leftJoin('a.SidActuTypeArticle sata')
                                ->where('a.is_active = ?', true)
                                ->andWhere('sata.sid_actu_type_id = ?', array($vars['type']))
                                ->orderBy('aTranslation.updated_at DESC')
                                ->limit($nbArticles)
                                ->execute();
                    }
                    foreach ($actuArticles as $actuArticle) { // on stock les NB actu article 
                        $arrayArticle[$actuArticle->id] = $actuArticle;
                        
                    }
                }
                break;

            default:
                // hors context, on renvoie la dernière actu mise à jour
                $actuArticles = Doctrine_Query::create()->from('SidActuArticle a')
                        ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                        ->leftJoin('a.SidActuTypeArticle sata')
                        ->where('a.is_active = ? and sata.sid_actu_type_id = ?', array(true,$vars['type']))
                        ->andWhere('(aTranslation.debut_date <= ? or aTranslation.debut_date is ?) and (aTranslation.fin_date >= ? or aTranslation.fin_date is ?)',array(date('Y-m-d'),NULL,date('Y-m-d'),NULL))
                        ->orderBy('aTranslation.updated_at DESC')
                        ->limit($nbArticles)
                        ->execute();
                
                if(count($actuArticles)){
                    foreach ($actuArticles as $actuArticle) { // on stock les NB actu article 
                        $arrayArticle[$actuArticle->id] = $actuArticle;
                    };
                }
        }
        // je vérifie que le titre de la page n'existe pas ou est égal à un espace
        if ($vars['titreBloc'] == NULL || $vars['titreBloc'] == " ") {
            // je vérifie le nbre d'article
            // si un seul , on affiche en titreBloc le titre de l'article
            if(count($actuArticles)){
                if ($vars['nbArticles'] == 1) {
                    $vars['titreBloc'] = current($arrayArticle)->getTitle();
                } 
                // si plusieurs articles, on affiche en titreBloc le nom de la page parente à ces articles
                elseif ($vars['nbArticles'] > 1){
                    $namePage = dmDb::table('DmPage')->findOneByModuleAndAction('sidActuArticle', 'list');
                    $vars['titreBloc'] = $namePage->getName();
                }
            }
        }
       // vérification qu'il y a du texte pour le lien, sinon, on vide $lien
        ($vars['lien'] != NULL || $vars['lien'] != " ") ? $lien = $vars['lien'] : $lien = '';
        return $this->getHelper()->renderPartial('actusDuCabinet', 'actuArticlesContextuel', array(
                    'articles' => $arrayArticle,
                    'nbArticles' => $vars['nbArticles'],
                    'titreBloc' => $vars['titreBloc'],
                    'lien' => $lien,
                    'length' => $vars['length'],
                    'chapo' => $vars['chapo'],
                    'width' => $vars['widthImage'],
                    'height' => $vars['heightImage'],
                    'withImage' => $vars['withImage']
                ));
    }

}
