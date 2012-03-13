<?php

class googleMapCabinetListGoogleMapCabinetView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc'
        ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();
        $dmPage = sfContext::getInstance()->getPage();
                
        $adresse = dmDb::table('SidCoordName')->createQuery('a')->where('is_active = ?',true)->orderBy('siege_social DESC')->execute();
        ($vars['titreBloc'] == NULL || $vars['titreBloc'] == " ") ? $vars['titreBloc'] = $dmPage->getName() :'';
        return $this->getHelper()->renderPartial('googleMapCabinet', 'listGoogleMapCabinet', array(
                    'adresse' => $adresse,
                    'titreBloc' => $vars['titreBloc']
                ));
    }

}
