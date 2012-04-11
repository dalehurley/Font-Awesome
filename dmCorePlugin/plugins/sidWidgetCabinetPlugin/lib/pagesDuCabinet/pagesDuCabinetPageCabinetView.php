<?php

class pagesDuCabinetPageCabinetView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'lien',
            'widthImage',
            'heightImage',
            'withImage'
        ));
    }

    /**
     * On choisit la page du cabinet à afficher
     * On choisit la longueur de texte
     * On choisit le titre du bloc (Facultatif, si il n'y a rien, on affiche l'intitulé de la page)
     *  
     */
    protected function doRender() {
        $vars = $this->getViewVars();
        
        $dmPage = sfContext::getInstance()->getPage();

        $pageCabinet = dmDb::table('SidCabinetPageCabinet')->findOneByIdAndIsActive($dmPage->record_id, true);

        if($vars['lien'] == NULL || $vars['lien'] == " "){
        $vars['lien'] = '';
        }
        return $this->getHelper()->renderPartial('pagesDuCabinet', 'pageCabinet', array(
                    
                    'pageCabinet' => $pageCabinet,
                    'titreBloc' => $vars['titreBloc'],
                    'lien' => $vars['lien'],
                    'width' => $vars['widthImage'],
                    'height' => $vars['heightImage'],
                    'withImage' => $vars['withImage'],
                ));
    }

}
