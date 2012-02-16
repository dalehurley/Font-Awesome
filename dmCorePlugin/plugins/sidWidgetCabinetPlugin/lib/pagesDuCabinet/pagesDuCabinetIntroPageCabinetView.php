<?php

class pagesDuCabinetIntroPageCabinetView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'page',
            'length',	
            'title_page',
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
        $arrayArticle = array();

        $pageCabinet = dmDb::table('SidCabinetPageCabinet')->findOneById($vars['page']);
        ($vars['withImage'] == true) ? (($pageCabinet->getImage() != '') ? $image = $pageCabinet->getImage() : $image = ''): $image = '';
        ($vars['title_page'] == NULL || $vars['title_page'] == " ") ? $vars['title_page'] = $pageCabinet->getTitle():'';
        ($vars['lien'] != NULL || $vars['lien'] != " ") ? $lien = $vars['lien'] : $lien = '';
		
        return $this->getHelper()->renderPartial('pagesDuCabinet', 'introPageCabinet', array(
                    
                    'pageCabinet' => $pageCabinet,
                    'length' => $vars['length'],
                    'titlePage' => $vars['title_page'],
                    'lien' => $vars['lien'],
                    'image' => $image,
                    'lien' => $lien,
                    'width' => $vars['widthImage'],
                    'height' => $vars['heightImage']
                ));
    }

}
