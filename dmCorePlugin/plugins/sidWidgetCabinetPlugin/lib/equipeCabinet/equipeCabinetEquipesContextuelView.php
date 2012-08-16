<?php

class equipeCabinetEquipesContextuelView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'lien',
            'nbArticles',
            'withImage',
            'widthImage',
            'mailTo'
        ));
    }
	
	public function getStylesheets() {
		//on créé un nouveau tableau car c'est un nouveau widget (si c'est une extension utiliser $stylesheets = parent::getStylesheets();)
		$stylesheets = array();
		
		//lien vers le js associé au menu
		$cssLink = '/theme/css/_templates/'.dmConfig::get('site_theme').'/Widgets/EquipeCabinetEquipesContextuel/EquipeCabinetEquipesContextuel.css';
		//chargement de la CSS si existante
		if (is_file(sfConfig::get('sf_web_dir') . $cssLink)) $stylesheets[] = $cssLink;
		
		return $stylesheets;
	}

    protected function doRender() {
        $vars = $this->getViewVars();
//        $arrayEquipe = array();
//        $arrayRubrique = array();
//        $arrayNomRubrique = array();

//        $idDmPage = sfContext::getInstance()->getPage()->id;
//        $dmPage = dmDb::table('DmPage')->findOneById($idDmPage);
        $dmPage = sfContext::getInstance()->getPage();
        $ancestors = $dmPage->getNode()->getAncestors();
        $nbArticles = ($vars['nbArticles'] == 0) ? '' : $vars["nbArticles"];
        switch ($dmPage->module . '/' . $dmPage->action) {
            case 'article/show':
                // il faut que je récupère l'id de la rubrique de la section
                // je récupère donc l'ancestor de la page courante pour extraire le record_id de ce dernier afin de retrouver la rubrique
//                $ancestors = $dmPage->getNode()->getAncestors();
                $recordId = $ancestors[count($ancestors) - 2]->getRecordId();
                $equipes = dmDb::table('SidCabinetEquipe')
                        ->createQuery('p')
                        ->leftJoin('p.SidCabinetEquipeSidRubrique sas')
                        ->leftJoin('sas.SidRubrique s')
                        ->where('s.id = ? and p.is_active = ? ', array($recordId,true))
                        ->orderBy('RANDOM()')
                        ->limit($nbArticles)
                        ->execute();
                
                
                // si il n'y a pas de contexte ou pas de collaborateur affecté à une rubrique
                if (count($equipes) == 0) {
                    $equipes = '';
                    $equipes = dmDb::table('SidCabinetEquipe')
                            ->createQuery('p')
                            ->where('p.is_active = ? ', array(true))
                            ->orderBy('RANDOM()')
                            ->limit($nbArticles)
                            ->execute();
                };
                break;
            case 'section/show':
                // il faut que je récupère l'id de la rubrique de la section
                // je récupère donc l'ancestor de la page courante pour extraire le record_id de ce dernier afin de retrouver la rubrique
//                $ancestors = $this->context->getPage()->getNode()->getAncestors();
                $recordId = $ancestors[count($ancestors) - 1]->getRecordId();
                $equipes = dmDb::table('SidCabinetEquipe')
                        ->createQuery('p')
                        ->leftJoin('p.SidCabinetEquipeSidRubrique sas')
                        ->leftJoin('sas.SidRubrique s')
                        ->where('s.id = ? and p.is_active = ?', array($recordId,true))
                        ->orderBy('RANDOM()')
                        ->limit($nbArticles)
                        ->execute();
                // si il n'y a pas de contexte ou pas de collaborateur affecté à une rubrique
                if (count($equipes) == 0) {
                    $equipes = '';
                    $equipes = dmDb::table('SidCabinetEquipe')
                            ->createQuery('p')
                            ->where('p.is_active = ? ', array(true))
                            ->orderBy('RANDOM()')
                            ->limit($nbArticles)
                            ->execute();
                    
                };
                break;
//            case 'rubrique/show':
//                // toutes les sections de la rubrique contextuelle
//                $rubriques = dmDb::table('SidRubrique')->findById($dmPage->record_id);
//                // on parcourt les sections pour extraire les articles
//                foreach ($rubriques as $rubrique) {
//                    $rubriqueEquipes = dmDb::table('SidCabinetEquipe')
//                            ->createQuery('p')
//                            ->leftJoin('p.SidCabinetEquipeSidRubrique sas')
//                            ->leftJoin('sas.SidRubrique s')
//                            ->where('s.id = ? and p.is_active = ?', array($rubrique->id,true))
//                            ->limit($vars['nbArticles'])
//                            ->execute();
//
//                    if (count($rubriqueEquipes) == 0) {
//                        $rubriqueEquipes = '';
//                        $rubriqueEquipes = dmDb::table('SidCabinetEquipe')
//                                ->createQuery('p')
//                                ->where('p.is_active = ? ', array(true))
//                                ->orderBy('RANDOM()')
//                                ->limit($vars['nbArticles'])
//                                ->execute();
//                    }
//                };
//                break;
            // on n'affiche rien si on est sur une page du module equipe
//            case 'pageCabinet/equipe':
//                $rubriqueEquipes = array();
//                break;
            // on affiche les equipes ayant les mêmes rubriques que la page actu du cabinet
            case 'sidActuArticle/show':
                // on cherche la rubrique de l'article
                $rubriques = dmDb::table('SidActuArticleSidRubrique')->findBySidActuArticleId($dmPage->record_id);
                // on parcourt les sections pour extraire les articles
                foreach ($rubriques as $rubrique) {
                    $equipes = dmDb::table('SidCabinetEquipe')
                            ->createQuery('p')
                            ->leftJoin('p.SidCabinetEquipeSidRubrique sas')
                            ->leftJoin('sas.SidRubrique s')
                            ->where('s.id = ? and p.is_active = ? ', array($rubrique->sidRubriqueId,true))
                            ->orderBy('RANDOM()')
                            ->limit($nbArticles)
                            ->execute();
                
                }

                    if (count($rubriques) == 0) {
                        $equipes = '';
                        $equipes = dmDb::table('SidCabinetEquipe')
                                ->createQuery('p')
                                ->where('p.is_active = ? ', array(true))
                                ->orderBy('RANDOM()')
                                ->limit($nbArticles)
                                ->execute();
                    }
                
                break;
            case 'mission/show':
               // on cherche la rubrique de l'article
               $rubriques = dmDb::table('SidCabinetMissionSidRubrique')->findBySidCabinetMissionId($dmPage->record_id);
               if (count($rubriques) == 0) {
                       $actuEquipes = '';
                       $actuEquipes = dmDb::table('SidCabinetEquipe')
                               ->createQuery('p')
                               ->where('p.is_active = ? ', array(true))
                               ->orderBy('RANDOM()')
                               ->limit($nbArticles)
                               ->execute();
                } else {
                    // on parcourt les sections pour extraire les articles
                    foreach ($rubriques as $rubrique) {
                        $equipes = dmDb::table('SidCabinetEquipe')
                               ->createQuery('p')
                               ->leftJoin('p.SidCabinetEquipeSidRubrique sas')
                               ->leftJoin('sas.SidRubrique s')
                               ->where('s.id = ? and p.is_active = ?', array($rubrique->sidRubriqueId,true))
                               ->limit($nbArticles)
                               ->execute();
                    }
                }
               
               break;
            case 'pageCabinet/list':
                $equipes = dmDb::table('SidCabinetEquipe')
                        ->createQuery('p')
                        ->where('p.is_active = ? ', array(true))
                        ->orderBy('p.position')
                        ->limit($nbArticles)
                        ->execute();
            break;
            default:
                $equipes = dmDb::table('SidCabinetEquipe')
                        ->createQuery('p')
                        ->where('p.is_active = ? ', array(true))
                        ->orderBy('RANDOM()')
                        ->limit($nbArticles)
                        ->execute();
                
            }  
            
            $linkAllEquipe = dmDb::table('dmPage')->findOneByModuleAndAction('renseignements', 'list');
        return $this->getHelper()->renderPartial('equipeCabinet', 'equipesContextuel', array(
                    'equipes' => (isset($equipes))? $equipes : null,
                    'titreBloc' => $vars['titreBloc'],
                    'linkAllEquipe' => $linkAllEquipe,
                    'lien' => $vars['lien'],
                    'withImage' => $vars['withImage'],
                    'width' => $vars['widthImage'],
                    'mailTo' => $vars['mailTo'],
                ));
    }

}
