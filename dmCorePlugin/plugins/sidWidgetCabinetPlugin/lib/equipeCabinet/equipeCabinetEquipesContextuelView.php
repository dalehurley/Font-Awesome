<?php

class equipeCabinetEquipesContextuelView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'titreLien',
            'nb',
            'lenght'
        ));
    }
	
	public function getStylesheets() {
		//on créé un nouveau tableau car c'est un nouveau widget (si c'est une extension utiliser $stylesheets = parent::getStylesheets();)
		$stylesheets = array();
		
		//lien vers le js associé au menu
		$cssLink = sidSPLessCss::getCssPathTemplate(). '/Widgets/EquipeCabinetEquipesContextuel/EquipeCabinetEquipesContextuel.css';
		//chargement de la CSS si existante
		if (is_file(sfConfig::get('sf_web_dir') . $cssLink)) $stylesheets[] = $cssLink;
		
		return $stylesheets;
	}

    protected function doRender() {
        $vars = $this->getViewVars();
        $arrayEquipe = array();
        $arrayRubrique = array();
        $arrayNomRubrique = array();

        $idDmPage = sfContext::getInstance()->getPage()->id;
        $dmPage = dmDb::table('DmPage')->findOneById($idDmPage);
        switch ($dmPage->module . '/' . $dmPage->action) {
            case 'article/show':
                // il faut que je récupère l'id de la rubrique de la section
                // je récupère donc l'ancestor de la page courante pour extraire le record_id de ce dernier afin de retrouver la rubrique
                $ancestors = $this->context->getPage()->getNode()->getAncestors();
                $recordId = $ancestors[count($ancestors) - 2]->getRecordId();
                $rubriqueEquipes = dmDb::table('SidCabinetEquipe')
                        ->createQuery('p')
                        ->leftJoin('p.SidCabinetEquipeSidRubrique sas')
                        ->leftJoin('sas.SidRubrique s')
                        ->where('s.id = ? ', array($recordId))
                        ->limit($vars['nb'])
                        ->execute();
                // si il n'y a pas de contexte ou pas de collaborateur affecté à une rubrique
                if (count($rubriqueEquipes) == 0) {
                    $rubriqueEquipes = '';
                    $rubriqueEquipes = dmDb::table('SidCabinetEquipe')
                            ->createQuery('p')
                            ->where('p.is_active = ? ', array(true))
                            ->orderBy('RANDOM()')
                            ->limit($vars['nb'])
                            ->execute();
                };
                break;
            case 'section/show':
                // il faut que je récupère l'id de la rubrique de la section
                // je récupère donc l'ancestor de la page courante pour extraire le record_id de ce dernier afin de retrouver la rubrique
                $ancestors = $this->context->getPage()->getNode()->getAncestors();
                $recordId = $ancestors[count($ancestors) - 1]->getRecordId();
                $rubriqueEquipes = dmDb::table('SidCabinetEquipe')
                        ->createQuery('p')
                        ->leftJoin('p.SidCabinetEquipeSidRubrique sas')
                        ->leftJoin('sas.SidRubrique s')
                        ->where('s.id = ? ', array($recordId))
                        ->limit($vars['nb'])
                        ->execute();
                // si il n'y a pas de contexte ou pas de collaborateur affecté à une rubrique
                if (count($rubriqueEquipes) == 0) {
                    $rubriqueEquipes = '';
                    $rubriqueEquipes = dmDb::table('SidCabinetEquipe')
                            ->createQuery('p')
                            ->where('p.is_active = ? ', array(true))
                            ->orderBy('RANDOM()')
                            ->limit($vars['nb'])
                            ->execute();
                };
                break;
            case 'rubrique/show':
                // toutes les sections de la rubrique contextuelle
                $rubriques = dmDb::table('SidRubrique')->findById($dmPage->record_id);
                // on parcourt les sections pour extraire les articles
                foreach ($rubriques as $rubrique) {
                    $rubriqueEquipes = dmDb::table('SidCabinetEquipe')
                            ->createQuery('p')
                            ->leftJoin('p.SidCabinetEquipeSidRubrique sas')
                            ->leftJoin('sas.SidRubrique s')
                            ->where('s.id = ? ', array($rubrique->id))
                            ->limit($vars['nb'])
                            ->execute();

                    if (count($rubriqueEquipes) == 0) {
                        $rubriqueEquipes = '';
                        $rubriqueEquipes = dmDb::table('SidCabinetEquipe')
                                ->createQuery('p')
                                ->where('p.is_active = ? ', array(true))
                                ->orderBy('RANDOM()')
                                ->limit($vars['nb'])
                                ->execute();
                    }
                };
                break;
            // on n'affiche rien si on est sur une page du module equipe
            case 'pageCabinet/equipe':
                $rubriqueEquipes = array();
                break;
            // on affiche les equipes ayant les mêmes rubriques que la page actu du cabinet
            case 'sidActuArticle/show':
                // on cherche la rubrique de l'article
                $rubriques = dmDb::table('SidActuArticleSidRubrique')->findBySidActuArticleId($dmPage->record_id);
                // on parcourt les sections pour extraire les articles
                foreach ($rubriques as $rubrique) {
                    $rubriqueEquipes = dmDb::table('SidCabinetEquipe')
                            ->createQuery('p')
                            ->leftJoin('p.SidCabinetEquipeSidRubrique sas')
                            ->leftJoin('sas.SidRubrique s')
                            ->where('s.id = ? ', array($rubrique->sidRubriqueId))
                            ->execute();
                
                }

                    if (count($rubriques) == 0) {
                        $rubriqueEquipes = '';
                        $rubriqueEquipes = dmDb::table('SidCabinetEquipe')
                                ->createQuery('p')
                                ->where('p.is_active = ? ', array(true))
                                ->orderBy('RANDOM()')
                                ->limit($vars['nb'])
                                ->execute();
                    }
                
                break;
            case 'mission/show':
                // on cherche la rubrique de l'article
                $rubriques = dmDb::table('SidCabinetMissionSidRubrique')->findBySidCabinetMissionId($dmPage->record_id);
                // on parcourt les sections pour extraire les articles
                foreach ($rubriques as $rubrique) {
                    $rubriqueEquipes = dmDb::table('SidCabinetEquipe')
                            ->createQuery('p')
                            ->leftJoin('p.SidCabinetEquipeSidRubrique sas')
                            ->leftJoin('sas.SidRubrique s')
                            ->where('s.id = ? ', array($rubrique->sidRubriqueId))
                            ->execute();
                }
                    if (count($rubriques) == 0) {
                        
                        $actuEquipes = '';
                        $actuEquipes = dmDb::table('SidCabinetEquipe')
                                ->createQuery('p')
                                ->where('p.is_active = ? ', array(true))
                                ->orderBy('RANDOM()')
                                ->limit($vars['nb'])
                                ->execute();
                    }
                
                break;

            default:
                $rubriqueEquipes = dmDb::table('SidCabinetEquipe')
                        ->createQuery('p')
                        ->where('p.is_active = ? ', array(true))
                        ->orderBy('RANDOM()')
                        ->limit($vars['nb'])
                        ->execute();
                
            }    
                // je stocke les collaborateurs et leur(s) rubrique(s) respective(s)
                foreach ($rubriqueEquipes as $rubriqueEquipe) { // on stock les NB collaborateur 
                    $arrayEquipe[$rubriqueEquipe->id] = $rubriqueEquipe;
                    $rubriques = $rubriqueEquipe->getMRubriques();
                    $nomRubrique = "";
                    // je resort les rubriques
                    if(count($rubriques) == 1){
                        // je prépare la variable pour l'affichage de la rubrique
                        $pageRubriques = dmDb::table('DmPage')->findByModuleAndActionAndRecordId('rubrique', 'show', $rubriques[0]->id);
                        $arrayNomRubrique[$rubriqueEquipe->id] = $pageRubriques[0]->name;
                    }
                    if(count($rubriques) > 1) {
                        $nomRubrique = '';
                            // je prépare la variable pour l'affichage des rubriques
                            foreach($rubriques as $i=>$rubrique){
                                $pageRubriques = dmDb::table('DmPage')->findByModuleAndActionAndRecordId('rubrique', 'show', $rubrique->id);
                                if($i > 0){
                                    $nomRubrique .= ' - '.$pageRubriques[0]->name;
                                }
                                else $nomRubrique .= $pageRubriques[0]->name;
                            }
                            $arrayNomRubrique[$rubriqueEquipe->id] = $nomRubrique;
                    }
                    
                }
        

        $pageEquipe = dmDb::table('dmPage')->createQuery('a')->where('a.module = ? and a.action = ? and a.record_id = ?', array('pageCabinet', 'equipe', 0))->execute();
        return $this->getHelper()->renderPartial('equipeCabinet', 'equipesContextuel', array(
                    'equipes' => $arrayEquipe,
                    'nb' => $vars['nb'],
                    'titreBloc' => $vars['titreBloc'],
                    'titreLien' => $vars['titreLien'],
                    'pageEquipe' => $pageEquipe[0],
                    'lenght' => $vars['lenght'],
                    'rubrique' => $arrayRubrique,
                    'nomRubrique' => $arrayNomRubrique
                ));
    }

}
