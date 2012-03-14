<?php

class implantationCabinetListImplantationView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc'
        ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();
        $dmPage = sfContext::getInstance()->getPage();
                
        $adresses = dmDb::table('SidCoordName')->createQuery('a')->where('a.is_active = ?',true)->orderBy('a.siege_social DESC')->execute();
        ($vars['titreBloc'] == NULL || $vars['titreBloc'] == " ") ? $vars['titreBloc'] = $dmPage->getName() :'';
        return $this->getHelper()->renderPartial('googleMapCabinet', 'listGoogleMapCabinet', array(
                    'adresses' => $adresses,
                    'titreBloc' => $vars['titreBloc']
                ));
    }

}
