<?php

class handWidgetsEquipeShowView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc'
        ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();
        $arrayEquipes = array();
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
        return $this->getHelper()->renderPartial('handWidgets', 'equipeShow', array(
                    'equipes' => $arrayEquipes,
                    'titreBloc' => $vars['titreBloc']
                ));
    }

}
