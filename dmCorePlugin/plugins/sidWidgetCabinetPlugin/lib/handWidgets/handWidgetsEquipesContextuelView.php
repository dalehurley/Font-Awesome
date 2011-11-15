<?php

class handWidgetsEquipesContextuelView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'titreLien',	    
            'nb'
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
        $arrayEquipe = array();

	$idDmPage = sfContext::getInstance()->getPage()->id;
	$dmPage = dmDb::table('DmPage')->findOneById($idDmPage);
	//$arrayArticle[] = $dmPage->module.' - '.$dmPage->action.' - '.$dmPage->record_id;
	
	switch ($dmPage->module.'/'.$dmPage->action) {
	    case 'section/show':
                            // il faut que je récupère l'id de la rubrique de la section
                            // je récupère donc l'ancestor de la page courante pour extraire le record_id de ce dernier afin de retrouver la rubrique
                            $ancestors = $this->context->getPage()->getNode()->getAncestors();
                            $recordId = $ancestors[count($ancestors)-1]->getRecordId();
		$actuEquipes = dmDb::table('SidCabinetEquipe')
			->createQuery('p')
			->leftJoin('p.SidCabinetEquipeSidRubrique sas')
			->leftJoin('sas.SidRubrique s')
			->where('s.id = ? ', array($recordId))
			->limit($vars['nb'])
			->execute();
		foreach($actuEquipes as $actuEquipe){ // on stock les NB actu article 
		    $arrayEquipe[$actuEquipe->id] = $actuEquipe;
		}
		break;
	    case 'rubrique/show':
		// toutes les sections de la rubrique contextuelle
		$rubriques = dmDb::table('SidRubrique')->findById($dmPage->record_id);
		// on parcourt les sections pour extraire les articles
		foreach ($rubriques as $rubrique) {
		    $actuEquipes = dmDb::table('SidCabinetEquipe')
			    ->createQuery('p')
			    ->leftJoin('p.SidCabinetEquipeSidRubrique sas')
			    ->leftJoin('sas.SidRubrique s')
			    ->where('s.id = ? ', array($rubrique->id))
			    ->limit($vars['nb'])
			    ->execute();
		    foreach ($actuEquipes as $actuEquipe) { // on stock les NB actu article 
			$arrayEquipe[$actuEquipe->id] = $actuEquipe;

		    }
		}
		break;
                // on n'affiche rien si on est sur une page du module equipe
                case 'equipe/show':
                        break;
                    case 'equipe/list':
                        break;
                    // on affiche les equipes ayant les mêmes rubriques que la page actu du cabinet
                     case 'sidActuArticle/show':
                         // on cherche la rubrique de l'article
                         $rubriques = dmDb::table('SidActuArticleSidRubrique')->findBySidActuArticleId($dmPage->record_id);
//                         $rubriques = dmDb::table('SidRubrique')->findById($dmPage->record_id);
		// on parcourt les sections pour extraire les articles
		foreach ($rubriques as $rubrique) {
		    $actuEquipes = dmDb::table('SidCabinetEquipe')
			    ->createQuery('p')
			    ->leftJoin('p.SidCabinetEquipeSidRubrique sas')
			    ->leftJoin('sas.SidRubrique s')
			    ->where('s.id = ? ', array($rubrique->sidRubriqueId))
			    ->execute();
                    
                                    foreach ($actuEquipes as $actuEquipe) { // on stock les NB actu article 
                                        // on compte le nbre de missions pour ne stocker que la quantité demandée
                                        if (count($arrayEquipe) < $vars['nb']) {
                                            $arrayEquipe[$actuEquipe->id] = $actuEquipe;
                                        }
                                    }
                }
                break;
                case 'mission/show':
                         // on cherche la rubrique de l'article
                         $rubriques = dmDb::table('SidCabinetMissionSidRubrique')->findBySidCabinetMissionId($dmPage->record_id);
//                         $rubriques = dmDb::table('SidRubrique')->findById($dmPage->record_id);
		// on parcourt les sections pour extraire les articles
		foreach ($rubriques as $rubrique) {
		    $actuEquipes = dmDb::table('SidCabinetEquipe')
			    ->createQuery('p')
			    ->leftJoin('p.SidCabinetEquipeSidRubrique sas')
			    ->leftJoin('sas.SidRubrique s')
			    ->where('s.id = ? ', array($rubrique->sidRubriqueId))
			    ->execute();
                    
                                    foreach ($actuEquipes as $actuEquipe) { // on stock les NB actu article 
                                        // on compte le nbre de missions pour ne stocker que la quantité demandée
                                         if (count($arrayEquipe) < $vars['nb']) {
                                            $arrayEquipe[$actuEquipe->id] = $actuEquipe;
                                        }
                                    }
                }
                break;
	    default:
		// hors context, on ne renvoie aucun article
	}
        
        $pageEquipe = dmDb::table('dmPage')->createQuery('a')->where('a.module = ? and a.action = ? and a.record_id = ?', array('pageCabinet', 'equipe',0 ))->execute();
        
        return $this->getHelper()->renderPartial('handWidgets', 'equipesContextuel', array(
                    'equipes' => $arrayEquipe,
                    'nb' => $vars['nb'],
                    'titreBloc' => $vars['titreBloc'],
                    'titreLien' => $vars['titreLien'],
                    'pageEquipe' => $pageEquipe[0]
                ));
    }

}
