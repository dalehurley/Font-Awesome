<?php

class dmWidgetContentLegalNoticesView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'text',
            'defaultInfos',
            'titreBloc'
        ));
    }
	
	public function getStylesheets() {
		//on créé un nouveau tableau car c'est un nouveau widget (si c'est une extension utiliser $stylesheets = parent::getStylesheets();)
		$stylesheets = array();
		
		//lien vers le js associé au menu
//		$cssLink = '/theme/css/_templates/'.dmConfig::get('site_theme').'/Widgets/AdressesMultiples/AdressesMultiples.css';
		//chargement de la CSS si existante
//		if (is_file(sfConfig::get('sf_web_dir') . $cssLink)) $stylesheets[] = $cssLink;
		
		return $stylesheets;
	}

    protected function doRender() {
        $vars = $this->getViewVars();
        $officeInfos = dmDb::table('SidCoordName')->findOneBySiegeSocialAndIsActive(TRUE,TRUE);
        
        return $this->getHelper()->renderPartial('dmWidgetContent', 'legalNotices', array(
                    'text' => $vars['text'],
                    'titreBloc' => $vars['titreBloc'],
                    'defaultInfos' => $vars['defaultInfos'],
                    'officeInfos' => $officeInfos
                    
                ));
    }

}