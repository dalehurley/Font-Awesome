<?php

class equipeCabinetEquipeShowView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc'
        ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();
        $arrayEquipes = array();
        $arrayNomRubrique = array();
        $idDmPage = sfContext::getInstance()->getPage()->id;
        $dmPage = dmDb::table('DmPage')->findOneById($idDmPage);

        $equipes = Doctrine_Query::create()
                ->from('SidCabinetEquipe a')
                ->where('a.is_active = ? ', array(true))
                ->orderBy('a.implentation_id')
                ->execute();

        foreach ($equipes as $equipe) { // on stock les NB actu article 
            $arrayEquipes[$equipe->id] = $equipe;
        };
        // je stocke les collaborateurs et leur(s) rubrique(s) respective(s)
        foreach ($equipes as $equipe) { // on stock les NB actu article 
            $arrayEquipe[$equipe->id] = $equipe;
            $rubriques = $equipe->getMRubriques();
            $nomRubrique = "";
            // je resort les rubriques
            if (count($rubriques) == 1) {
                // je prépare la variable pour l'affichage de la rubrique
                $pageRubriques = dmDb::table('DmPage')->findByModuleAndActionAndRecordId('rubrique', 'show', $rubriques[0]->id);
                $arrayNomRubrique[$equipe->id] = $pageRubriques[0]->name;
            }
            if (count($rubriques) != 1) {
                $nomRubrique = '';
                // je prépare la variable pour l'affichage des rubriques
                foreach ($rubriques as $rubrique) {
                    $pageRubriques = dmDb::table('DmPage')->findByModuleAndActionAndRecordId('rubrique', 'show', $rubrique->id);
                    $nomRubrique .= $pageRubriques[0]->name . ' ';
                }
                $arrayNomRubrique[$equipe->id] = $nomRubrique;
            }
                    
        }
        return $this->getHelper()->renderPartial('equipeCabinet', 'equipeShow', array(
                    'equipes' => $arrayEquipes,
                    'titreBloc' => $vars['titreBloc'],
                    'nomRubrique' => $arrayNomRubrique
                ));
    }

}
