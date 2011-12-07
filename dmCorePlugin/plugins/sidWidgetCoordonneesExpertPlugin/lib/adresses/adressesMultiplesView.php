<?php

class adressesMultiplesView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc'
        ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();
                
        $adresses = dmDb::table('SidCoordName')
                ->createQuery()
                ->where('is_active = ?',true)
                ->orderBy('siege_social DESC')
                ->execute();
        return $this->getHelper()->renderPartial('adresses', 'multiples', array(
                    'adresses' => $adresses,
                    'titreBloc' => $vars['titreBloc']
                ));
    }

}
