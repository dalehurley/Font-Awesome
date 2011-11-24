<?php

class handWidgetsPageCabinetView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'title_page',
            'lien'
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
        
        $idDmPage = sfContext::getInstance()->getPage()->id;
        $dmPage = dmDb::table('DmPage')->findOneById($idDmPage);

        $pageCabinet = dmDb::table('SidCabinetPageCabinet')->findOneByIdAndIsActive($dmPage->record_id, true);
        if($vars['title_page'] == NULL || $vars['title_page'] == " "){
        $vars['title_page'] = $pageCabinet->getTitle();
        }
        if($vars['lien'] == NULL || $vars['lien'] == " "){
        $vars['lien'] = __('Contact');
        }
        return $this->getHelper()->renderPartial('handWidgets', 'pageCabinet', array(
                    
                    'pageCabinet' => $pageCabinet,
                    'titlePage' => $vars['title_page'],
                    'lien' => $vars['lien']
                ));
    }

}
