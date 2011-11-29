<?php

class handWidgetsMissionsListView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'nbMissions',
            'longueurTexte',
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
        $arrayMissions = array();
        $idDmPage = sfContext::getInstance()->getPage()->id;
        $dmPage = dmDb::table('DmPage')->findOneById($idDmPage);
        
        switch ($dmPage->module.'/'.$dmPage->action){
            
            case 'mission/show':
                if($vars['nbMissions'] == 0) { $nb = dmDb::table('SidCabinetMission')->count();}
        else $nb = $vars['nbMissions'];

        $missions = Doctrine_Query::create()
                ->from('SidCabinetMission a')
                ->where('a.is_active = ? and a.id <> ?', array(true,$dmPage->record_id))
                ->orderBy('a.updated_at DESC')
                ->limit($nb)
                ->execute();
        
        foreach ($missions as $mission) { // on stock les NB actu article 
                    $arrayMissions[$mission->id] = $mission;
                }
                break;
        default:
        if($vars['nbMissions'] == 0) { $nb = dmDb::table('SidCabinetMission')->count();}
        else $nb = $vars['nbMissions'];

        $missions = Doctrine_Query::create()
                ->from('SidCabinetMission a')
                ->where('a.is_active = ? ', array(true))
                ->orderBy('a.updated_at DESC')
                ->limit($nb)
                ->execute();
        
        foreach ($missions as $mission) { // on stock les NB actu article 
                    $arrayMissions[$mission->id] = $mission;
                };
                
       break;
                
        }

        return $this->getHelper()->renderPartial('handWidgets', 'missionsList', array(
                    'missions' => $arrayMissions,
                    'nbMissions' => $vars['nbMissions'],
                    'titreBloc' => $vars['titreBloc'],
                    'longueurTexte' => $vars['longueurTexte'],
                    'chapo' => $vars['chapo'],
            
                ));
    }

}
