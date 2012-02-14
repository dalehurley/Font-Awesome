<?php

class pagesDuCabinetIntroPageCabinetView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'page',
            'lenght',	
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
        $arrayArticle = array();

        $pageCabinet = dmDb::table('SidCabinetPageCabinet')->findOneById($vars['page']);
        if($vars['title_page'] == NULL || $vars['title_page'] == " "){
			$vars['title_page'] = $pageCabinet->getTitle();
			$vars['isTitleContent'] = true;
        }else{
			$vars['isTitleContent'] = false;
		}
		
        return $this->getHelper()->renderPartial('pagesDuCabinet', 'introPageCabinet', array(
                    
                    'pageCabinet' => $pageCabinet,
                    'lenght' => $vars['lenght'],
                    'titlePage' => $vars['title_page'],
                    'lien' => $vars['lien'],
					'isTitleContent' => $vars['isTitleContent']
                ));
    }

}
