<?php

class missionsMissionsListView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'nbArticles',
            'length',
            'chapo',
            'withImage',
            'widthImage',
            'heightImage',
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
        $dmPage = sfContext::getInstance()->getPage();
        $nbArticles = ($vars['nbArticles'] == 0) ? '' : $vars['nbArticles'];
        switch ($dmPage->module.'/'.$dmPage->action){
            
            case 'mission/show':
                

                $missions = Doctrine_Query::create()
                    ->from('SidCabinetMission a')
                    ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                    ->where('a.is_active = ? and a.id <> ?', array(true,$dmPage->record_id))
                    ->orderBy('aTranslation.updated_at DESC')
                    ->limit($nbArticles)
                    ->execute();
        
                foreach ($missions as $mission) { // on stock les NB actu article 
                    $arrayMissions[$mission->id] = $mission;
                }
            break;
            default:

                $missions = Doctrine_Query::create()
                    ->from('SidCabinetMission a')
                    ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                    ->where('a.is_active = ? ', array(true))
                    ->orderBy('aTranslation.updated_at DESC')
                    ->limit($nbArticles)
                    ->execute();
        
                foreach ($missions as $mission) { // on stock les NB actu article 
                    $arrayMissions[$mission->id] = $mission;
                };
                
            break;
                
        }

        return $this->getHelper()->renderPartial('missions', 'missionsList', array(
                    'missions' => $arrayMissions,
                    'titreBloc' => $vars['titreBloc'],
                    'length' => $vars['length'],
                    'chapo' => $vars['chapo'],
                    'withImage' => $vars['withImage'],
                    'width' => $vars['widthImage']
            
                ));
    }

}
