<?php

class handWidgetsIntroPageCabinetView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'page',
            'lenght',	
            'title_page'
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
        $arrayArticle = array();

        $pageCabinet = dmDb::table('SidCabinetPageCabinet')->findOneById($vars['page']);
        if($vars['title_page'] == NULL || $vars['title_page'] == " "){
        $vars['title_page'] = $pageCabinet->getTitle();
        }
        return $this->getHelper()->renderPartial('handWidgets', 'introPageCabinet', array(
                    
                    'pageCabinet' => $pageCabinet,
                    'lenght' => $vars['lenght'],
                    'titlePage' => $vars['title_page']
                ));
    }

}
