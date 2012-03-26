<?php

class implantationCabinetImplantationView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'withImage',
            'widthImage',
            'heightImage',
            'civ',
            'resume_team'
        ));
    }
	
    protected function doRender() {
        $vars = $this->getViewVars();
        $dmPage = sfContext::getInstance()->getPage();   
        $adresse = dmDb::table('SidCoordName')->findOneByIsActiveAndId(true,$dmPage->record_id);
        $arrayNomRubrique = array();
        $equipes = Doctrine_Query::create()
                ->from('SidCabinetEquipe a')
//                ->leftJoin('a.CoordName scn WITH a.coord_name_id =  scn.id')
                ->where('a.is_active = ? and a.coord_name_id = ? ', array(true,$dmPage->record_id))
//                ->orderBy('scn.siege_social DESC, a.position')
                ->orderBy('a.position')
                ->execute();
        
        
        foreach ($equipes as $equipe){
        // je stocke les collaborateurs et leur(s) rubrique(s) respective(s)
            $rubriques = $equipe->getMRubriques();
            $nomRubrique = "";
            // je resort les rubriques
            if (count($rubriques) == 1) {
                // je prépare la variable pour l'affichage de la rubrique
                $pageRubriques = dmDb::table('DmPage')->findByModuleAndActionAndRecordId('rubrique', 'show', $rubriques[0]->id);
                $arrayNomRubrique[$equipe->id] = $pageRubriques[0]->name;
            }
            if (count($rubriques) > 1) {
                $nomRubrique = '';
                // je prépare la variable pour l'affichage des rubriques
                foreach ($rubriques as $i=>$rubrique) {
                    $pageRubriques = dmDb::table('DmPage')->findByModuleAndActionAndRecordId('rubrique', 'show', $rubrique->id);
                    if($i > 0){
                    $nomRubrique .= ' - '.$pageRubriques[0]->name;
                    }
                    else $nomRubrique .= $pageRubriques[0]->name;
                }
                $arrayNomRubrique[$equipe->id] = $nomRubrique;
            }
    }
       
        if($vars['titreBloc'] == NULL || $vars['titreBloc'] == " "){
        $vars['titreBloc'] = $dmPage->getName();
        }
        return $this->getHelper()->renderPartial('implantationCabinet', 'implantation', array(
                    'adresse' => $adresse,
                    'equipes' => $equipes,
                    'titreBloc' => $vars['titreBloc'],
                    'civ' => $vars['civ'],
                    'withImage' => $vars['withImage'],
                    'width' => $vars['widthImage'],
                    'nomRubrique' => $arrayNomRubrique,
                    'visible_resume_team' => $vars['resume_team']
                ));
    }

}