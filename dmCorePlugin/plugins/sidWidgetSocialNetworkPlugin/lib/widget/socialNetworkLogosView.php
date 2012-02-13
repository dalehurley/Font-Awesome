<?php

class socialNetworkLogosView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'twitter',
            'facebook',
            'googleplus'
        ));
    }
	
	public function getStylesheets() {
		//on créé un nouveau tableau car c'est un nouveau widget (si c'est une extension utiliser $stylesheets = parent::getStylesheets();)
		$stylesheets = array();
		
		//lien vers le js associé au menu
		$cssLink = sidSPLessCss::getCssPathTemplate(). '/Widgets/SocialNetworkLogos/SocialNetworkLogos.css';
		//chargement de la CSS si existante
		if (is_file(sfConfig::get('sf_web_dir') . $cssLink)) $stylesheets[] = $cssLink;
		
		return $stylesheets;
	}

    protected function doRender() {
        $vars = $this->getViewVars();

        $arrayLogos = array(
            'facebook' => $vars['facebook'],
            'googleplus' => $vars['googleplus'],
            'linkedin' => $vars['linkedin'],
            'twitter' => $vars['twitter']
            );

       return $this->getHelper()->renderPartial('widget', 'socialNetworkLogos', array(
                    'logos' => $arrayLogos,
                ));
    }
}
