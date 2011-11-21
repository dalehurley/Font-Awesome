<?php

class handWidgetsMissionsContextuelView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'titreLien',
            'length',
            'nb',
            'chapo',
        ));
    }

    /**
     * On affiche NB articles Actu selon 3 types:
     * 1) il est sur une dmPage automatique de type "Rubrique" : on affiche les missions qui sont dans cette rubrique
     * 2) il est sur une dmPage automatique de type "Section" : on affiche les missions qui sont dans la rubrique de cette section
     * 3) il est sur une page du module mission : on n'affiche rien
     * 4) il est sur une page article du cabinet : on affaiche les missions qui ont la même rubrique
     * 5) il est sur une page ni Rubrique ni Section, automatique ou pas : on affiche les articles de la rubrique choisie par défaut
     *  
     */
    protected function doRender() {
        $vars = $this->getViewVars();
        $arrayMission = array();

        $idDmPage = sfContext::getInstance()->getPage()->id;
        $dmPage = dmDb::table('DmPage')->findOneById($idDmPage);
        //echo $dmPage->module.'/'.$dmPage->action;
        //$arrayArticle[] = $dmPage->module.' - '.$dmPage->action.' - '.$dmPage->record_id;

        switch ($dmPage->module . '/' . $dmPage->action) {
//                switch ($dmPage->module) {
            case 'section/show':
                // il faut que je récupère l'id de la rubrique de la section
                // je récupère donc l'ancestor de la page courante pour extraire le record_id de ce dernier afin de retrouver la rubrique
                $ancestors = $this->context->getPage()->getNode()->getAncestors();
                $recordId = $ancestors[count($ancestors) - 1]->getRecordId();
                $actuMissions = dmDb::table('SidCabinetMission')
                        ->createQuery('p')
                        ->leftJoin('p.SidCabinetMissionSidRubrique sas')
                        ->leftJoin('sas.SidRubrique s')
                        ->where('s.id = ? AND p.is_active  = ? ', array($recordId, true))
                        ->limit($vars['nb'])
                        ->execute();
                // Si il n'y a pas de missions associées, on en affiche Nb classée aléatoirement

                if (count($actuMissions) == 0) {
                    $actuMissions = '';
                    $actuMissions = Doctrine_Query::create()
                            ->from('SidCabinetMission p')
                            ->select('*')
                            ->where('p.is_active = ? ', array(true))
                            ->orderBy('RANDOM()')
                            ->limit($vars['nb'])
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
                            ->limit($vars['nb'])
                            ->execute();
                    // Si il n'y a pas de missions associées, on en affiche Nb classée aléatoirement

                    if (count($actuMissions) == 0) {
                        $actuMissions = '';
                        $actuMissions = Doctrine_Query::create()
                                ->from('SidCabinetMission p')
                                ->select('*')
                                ->where('p.is_active = ? ', array(true))
                                ->orderBy('RANDOM()')
                                ->limit($vars['nb'])
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
                // on cherche la rubrique de l'article
                $rubriques = dmDb::table('SidActuArticleSidRubrique')->findBySidActuArticleId($dmPage->record_id);
//                         $rubriques = dmDb::table('SidRubrique')->findById($dmPage->record_id);
                // on parcourt les sections pour extraire les articles
                foreach ($rubriques as $rubrique) {
                    $actuMissions = dmDb::table('SidCabinetMission')
                            ->createQuery('p')
                            ->leftJoin('p.SidCabinetMissionSidRubrique sas')
                            ->leftJoin('sas.SidRubrique s')
                            ->where('s.id = ? ', array($rubrique->sidRubriqueId))
                            ->execute();
                    // Si il n'y a pas de missions associées, on en affiche Nb classée aléatoirement

                    if (count($actuMissions) == 0) {
                        $actuMissions = '';
                        $actuMissions = Doctrine_Query::create()
                                ->from('SidCabinetMission p')
                                ->select('*')
                                ->where('p.is_active = ? ', array(true))
                                ->orderBy('RANDOM()')
                                ->limit($vars['nb'])
                                ->execute();
                    }
                    foreach ($actuMissions as $actuMission) { // on stock les NB actu article 
                        // on compte le nbre de missions pour ne stocker que la quantité demandée
                        if (count($arrayMission) < $vars['nb']) {
                            $arrayMission[$actuMission->id] = $actuMission;
                        }
                    }
                }
                break;
            case 'main/root':
                $actuMissions = dmDb::table('SidCabinetMission')
                        ->createQuery('a')
                        ->orderBy('a.updated_at DESC')
                        ->where('a.is_active = ?', array(true))
                        ->limit($vars['nb'])
                        ->execute();
                foreach ($actuMissions as $actuMission) { // on stock les NB actu article 
                    $arrayMission[$actuMission->id] = $actuMission;
                }

                break;
            default:
            // hors context, on ne renvoie aucun article
        }

        return $this->getHelper()->renderPartial('handWidgets', 'missionsContextuel', array(
                    'missions' => $arrayMission,
                    'titreBloc' => $vars['titreBloc'],
                    'titreLien' => $vars['titreLien'],
                    'chapo' => $vars['chapo'],
                    'length' => $vars['length']
                ));
    }

}
