<?php

class equipeCabinetEquipeShowView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'withImage',
            'widthImage',
            'heightImage'
            ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();
        $arrayNomRubrique = array();
        $arrayVilles = array();

        $equipes = Doctrine_Query::create()
                ->from('SidCabinetEquipe a')
                ->leftJoin('a.CoordName scn WITH a.coord_name_id =  scn.id')
                ->where('a.is_active = ? ', array(true))
                ->orderBy('scn.siege_social DESC, a.position')
                ->execute();
        
        // je trie les membres du cabinet par ville (les villes sont triées de manière à avoir le siège social en premier)
        foreach ($equipes as $equipe) { 
            $ville = $equipe->CoordName->ville;
            if(!array_key_exists($ville, $arrayVilles)){
                $arrayVilles[$ville] = array();
            }
            array_push($arrayVilles[$ville], $equipe);
            
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
        $vars['titreBloc'] = $pageCabinet->getTitle();
        }
        return $this->getHelper()->renderPartial('equipeCabinet', 'equipeShow', array(
                    'arrayVilles' => $arrayVilles,
                    'titreBloc' => $vars['titreBloc'],
                    'nomRubrique' => $arrayNomRubrique,
                    'withImage' => $vars['withImage'],
                    'width' => $vars['widthImage']
                ));
    }

}
