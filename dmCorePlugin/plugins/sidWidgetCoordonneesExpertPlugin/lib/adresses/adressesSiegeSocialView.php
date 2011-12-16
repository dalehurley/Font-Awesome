<?php

class adressesSiegeSocialView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc'
        ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();
                
        $adresse = dmDb::table('SidCoordName')->findOneByIsActiveAndSiegeSocial(true,true);
        return $this->getHelper()->renderPartial('adresses', 'siegeSocial', array(
                    'adresse' => $adresse,
                    'titreBloc' => $vars['titreBloc']
                ));
    }

}
