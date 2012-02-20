<?php

class pagesDuCabinetIntroPageCabinetView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'page',
            'length',	
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
        $arrayArticle = array();

        $pageCabinet = dmDb::table('SidCabinetPageCabinet')->findOneById($vars['page']);
        ($vars['withImage'] == true) ? (($pageCabinet->getImage()->checkFileExists() == true) ? $image = $pageCabinet->getImage() : $image = ''): $image = '';
        ($vars['titreBloc'] == NULL || $vars['titreBloc'] == " ") ? $vars['titreBloc'] = $pageCabinet->getTitle():'';
        ($vars['lien'] != NULL || $vars['lien'] != " ") ? $lien = $vars['lien'] : $lien = '';
		
        return $this->getHelper()->renderPartial('pagesDuCabinet', 'introPageCabinet', array(
                    'pageCabinet' => $pageCabinet,
                    'length' => $vars['length'],
//                    'image' => $image,
                    'lien' => $lien,
                    'titreBloc' => $vars['titreBloc'],
                    'width' => $vars['widthImage'],
                    'height' => $vars['heightImage'],
                    'withImage' => $vars['withImage'],
                ));
    }

}
