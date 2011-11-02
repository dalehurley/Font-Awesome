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
     * On affiche NB articles Actu selon 3 types:
     * 1) il est sur une dmPage automatique de type "Rubrique" : on affiche les articles qui sont dans les sections de cette rubrique
     * 2) il est sur une dmPage automatique de type "Section" : on affiche les articles qui sont dans cette section
     * 3) il est sur une page ni Rubrique ni Section, automatique ou pas : on affiche les articles de la rubrique choisie par défaut
     *  
     */
    protected function doRender() {
        $vars = $this->getViewVars();
        $arrayArticle = array();

	
	
		// hors context, on ne renvoie aucun article
	
        
        $pageCabinet = dmDb::table('SidCabinetPageCabinet')->createQuery('a')->where('a.id = ?' , $vars['page'])->execute();
        
        return $this->getHelper()->renderPartial('handWidgets', 'introPageCabinet', array(
                    
                    'pageCabinet' => $pageCabinet,
                    'lenght' => $vars['lenght'],
                ));
    }

}
