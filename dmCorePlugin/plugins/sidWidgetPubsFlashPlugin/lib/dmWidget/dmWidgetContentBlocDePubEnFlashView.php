<?php

class dmWidgetContentBlocDePubEnFlashView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'pubsId',
            'width',
            'height'
        ));
    }
	
	public function getStylesheets() {
		//on créé un nouveau tableau car c'est un nouveau widget (si c'est une extension utiliser $stylesheets = parent::getStylesheets();)
		$stylesheets = array();
		
		//lien vers le js associé au menu
		$cssLink = '/theme/css/_templates/'.dmConfig::get('site_theme').'/Widgets/ContentBlocDePubEnFlash/ContentBlocDePubEnFlash.css';
		//chargement de la CSS si existante
		if (is_file(sfConfig::get('sf_web_dir') . $cssLink)) $stylesheets[] = $cssLink;
		
		return $stylesheets;
	}

    /**
     * On affiche NB articles Actu selon 3 types:
     * 1) il est sur une dmPage automatique de type "Rubrique" : on affiche les articles qui sont dans les sections de cette rubrique
     * 2) il est sur une dmPage automatique de type "Section" : on affiche les articles qui sont dans cette section
     * 3) il est sur une page ni Rubrique ni Section, automatique ou pas : on affiche les articles de la rubrique choisie par défaut
     *  
     */
    protected function doRender() {
        
        $listPubsId = "";
        $vars = $this->getViewVars();
        // création de la chaine de caractères pour requète SQL
        $listPubsId = "'".implode("','", $vars['pubsId'])."'";
        // sélection d'une pub aléatoire parmi celles choisies lors de la pose du widget ($vars['pubsId']
        $pubsFlash = dmDb::table('DmMedia')
                        ->createQuery('a')
                        ->Where("a.file IN (".$listPubsId.")")
                        ->orderBy('RAND()')
                        ->limit('1')
                        ->execute();
        
        return $this->getHelper()->renderPartial('dmWidgetContentBlocDePubEnFlash', 'blocDePubsEnFlash', array(
                    'flash' => $pubsFlash[0]->getWebPath(),
                    'width' => $vars['width'],
                    'height' => $vars['height']
            
                ));        

}
}