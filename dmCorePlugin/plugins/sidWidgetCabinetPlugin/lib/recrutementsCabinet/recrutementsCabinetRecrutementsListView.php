<?php

class recrutementsCabinetRecrutementsListView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'nbRecrutements',
            'longueurTexte'
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
        $arrayRecrutements = array();
        $idDmPage = sfContext::getInstance()->getPage()->id;
        $dmPage = dmDb::table('DmPage')->findOneById($idDmPage);
        
        switch ($dmPage->module.'/'.$dmPage->action){
            
            case 'recrutement/show':
                if($vars['nbRecrutements'] == 0) { $nb = dmDb::table('SidCabinetRecrutement')->count();}
        else $nb = $vars['nbRecrutements'];

        $recrutements = Doctrine_Query::create()
                ->from('SidCabinetRecrutement a')
                ->where('a.is_active = ? and a.id <> ?', array(true,$dmPage->record_id))
                ->orderBy('a.updated_at DESC')
                ->limit($nb)
                ->execute();
        
        foreach ($recrutements as $recrutement) { // on stock les NB actu article 
                    $arrayRecrutements[$recrutement->id] = $recrutement;
                }
                break;
        default:
        if($vars['nbRecrutements'] == 0) { $nb = dmDb::table('SidCabinetRecrutement')->count();}
        else $nb = $vars['nbRecrutements'];

        $recrutements = Doctrine_Query::create()
                ->from('SidCabinetRecrutement a')
                ->where('a.is_active = ? ', array(true))
                ->orderBy('a.updated_at DESC')
                ->limit($nb)
                ->execute();
        
        foreach ($recrutements as $recrutement) { // on stock les NB actu article 
                    $arrayRecrutements[$recrutement->id] = $recrutement;
                };
                
       break;
                
        }

        return $this->getHelper()->renderPartial('recrutementsCabinet', 'recrutementsList', array(
                    'recrutements' => $arrayRecrutements,
                    'nbMissions' => $vars['nbRecrutements'],
                    'titreBloc' => $vars['titreBloc'],
                    'longueurTexte' => $vars['longueurTexte']
            
                ));
    }

}
