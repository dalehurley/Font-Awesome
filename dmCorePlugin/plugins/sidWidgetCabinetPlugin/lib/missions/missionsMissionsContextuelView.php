<?php

class missionsMissionsContextuelView extends dmWidgetPluginView {

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
            'withImage'
        ));
    }
	
	public function getStylesheets() {
		//on créé un nouveau tableau car c'est un nouveau widget (si c'est une extension utiliser $stylesheets = parent::getStylesheets();)
		$stylesheets = array();
		
		//lien vers le js associé au menu
		$cssLink = '/theme/css/_templates/'.dmConfig::get('site_theme').'/Widgets/MissionsMissionsContextuel/MissionsMissionsContextuel.css';
		//chargement de la CSS si existante
		if (is_file(sfConfig::get('sf_web_dir') . $cssLink)) $stylesheets[] = $cssLink;
		
		return $stylesheets;
	}

    protected function doRender() {
        $vars = $this->getViewVars();
        $arrayMission = array();

        $dmPage = sfContext::getInstance()->getPage();
        $nbArticles = ($vars['nbArticles'] == 0) ? '' : $vars['nbArticles'];
        switch ($dmPage->module . '/' . $dmPage->action) {
            case 'section/show':
                // il faut que je récupère l'id de la rubrique de la section
                // je récupère donc l'ancestor de la page courante pour extraire le record_id de ce dernier afin de retrouver la rubrique
                $ancestors = $dmPage->getNode()->getAncestors();
                $recordId = $ancestors[count($ancestors) - 1]->getRecordId();
                $actuMissions = dmDb::table('SidCabinetMission')
                        ->createQuery('p')
                        ->leftJoin('p.SidCabinetMissionSidRubrique sas')
                        ->leftJoin('sas.SidRubrique s')
                        ->where('s.id = ? AND p.is_active  = ? ', array($recordId, true))
                        ->limit($nbArticles)
                        ->execute();
                // Si il n'y a pas de missions associées, on en affiche Nb classée aléatoirement

                if (count($actuMissions) == 0) {
                    $actuMissions = '';
                    $actuMissions = Doctrine_Query::create()
                            ->from('SidCabinetMission p')
                            ->select('*')
                            ->where('p.is_active = ? ', array(true))
                            ->orderBy('RANDOM()')
                            ->limit($nbArticles)
                            ->execute();
                }
                foreach ($actuMissions as $actuMission) { // on stock les NB actu article 
                    $arrayMission[$actuMission->id] = $actuMission;
                }
                break;
            case 'rubrique/show':
                // toutes les sections de la rubrique contextuelle
                $rubriques = dmDb::table('SidRubrique')->findById($dmPage->record_id);
                // on parcourt les sections pour extraire les articles
                foreach ($rubriques as $rubrique) {
                    $actuMissions = dmDb::table('SidCabinetMission')
                            ->createQuery('p')
                            ->leftJoin('p.SidCabinetMissionSidRubrique sas')
                            ->leftJoin('sas.SidRubrique s')
                            ->where('s.id = ? ', array($rubrique->id))
                            ->limit($nbArticles)
                            ->execute();
                    // Si il n'y a pas de missions associées, on en affiche Nb classée aléatoirement

                    if (count($actuMissions) == 0) {
                        $actuMissions = '';
                        $actuMissions = Doctrine_Query::create()
                                ->from('SidCabinetMission p')
                                ->select('*')
                                ->where('p.is_active = ? ', array(true))
                                ->orderBy('RANDOM()')
                                ->limit($nbArticles)
                                ->execute();
                    }
                    foreach ($actuMissions as $actuMission) { // on stock les NB actu article 
                        $arrayMission[$actuMission->id] = $actuMission;
                    }
                }
                break;
            // on n'affiche rien si on est sur une page du module mission
            case 'mission/show':
                break;
            case 'mission/list':
                break;

            // on affiche les missions ayant les mêmes rubriques que la page actu du cabinet
            case 'sidActuArticle/show':
//                // on cherche la rubrique de l'article
//                $rubriques = dmDb::table('SidActuArticleSidRubrique')->findBySidActuArticleId($dmPage->record_id);
//                // on parcourt les sections pour extraire les articles
//                foreach ($rubriques as $rubrique) {
//                    $actuMissions = dmDb::table('SidCabinetMission')
//                            ->createQuery('p')
//                            ->leftJoin('p.SidCabinetMissionSidRubrique sas')
//                            ->leftJoin('sas.SidRubrique s')
//                            ->where('s.id = ? ', array($rubrique->sidRubriqueId))
//                            ->execute();
//                    // Si il n'y a pas de missions associées, on en affiche Nb classée aléatoirement
//
//                    if (count($actuMissions) == 0) {
//                        $actuMissions = '';
//                        $actuMissions = Doctrine_Query::create()
//                                ->from('SidCabinetMission p')
//                                ->select('*')
//                                ->where('p.is_active = ? ', array(true))
//                                ->orderBy('RANDOM()')
//                                ->limit($vars['nbArticles'])
//                                ->execute();
//                    }
//                    foreach ($actuMissions as $actuMission) { // on stock les NB actu article 
//                        // on compte le nbre de missions pour ne stocker que la quantité demandée
//                        if (count($arrayMission) < $vars['nb']) {
//                            $arrayMission[$actuMission->id] = $actuMission;
//                        }
//                    }
//                }
                break;

            case 'pageCabinet/equipe':
                break;
            default:
                $actuMissions = dmDb::table('SidCabinetMission')
                        ->createQuery('a')
                        ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                        ->orderBy('aTranslation.updated_at DESC')
                        ->where('a.is_active = ?', array(true))
                        ->limit($nbArticles)
                        ->execute();
                foreach ($actuMissions as $actuMission) { // on stock les NB actu article 
                    $arrayMission[$actuMission->id] = $actuMission;
                }
        }
        
        
        
// je vérifie que le titre de la page n'esxiste pas ou est égal à un espace
        if ($vars['titreBloc'] == NULL || $vars['titreBloc'] == " ") {
            // je vérifie le nbre d'article
            // si un seul , on affiche en titre le titre de l'article
            if ($vars['nbArticles'] == 1) {
                $vars['titreBloc'] = current($arrayMission)->getTitle();
            } 
            // si plusieurs articles, on affiche en titre le nom de la page parente à ces articles
            elseif ($vars['nbArticles'] > 1){
                $namePage = dmDb::table('DmPage')->findOneByModuleAndAction('sidActuArticle', 'list');
                $vars['titreBloc'] = $namePage->getName();
            }
        }
       
        ($vars['lien'] != NULL || $vars['lien'] != " ") ? $lien = $vars['lien'] : $lien = '';
        return $this->getHelper()->renderPartial('missions', 'missionsContextuel', array(
                    'articles' => $arrayMission,
                    'nbArticles' => $vars['nbArticles'],
                    'titreBloc' => $vars['titreBloc'],
                    'lien' => $lien,
                    'length' => $vars['length'],
                    'chapo' => $vars['chapo'],
                    'width' => $vars['widthImage'],
                    'height' => $vars['heightImage']
                ));

//        return $this->getHelper()->renderPartial('missions', 'missionsContextuel', array(
//                    'missions' => $arrayMission,
//                    'titreBloc' => $vars['titreBloc'],
//                    'titreLien' => $vars['titreLien'],
//                    'chapo' => $vars['chapo'],
//                    'length' => $vars['length'],
//                    'nb' => $vars['nb']
//                ));
    }

}
