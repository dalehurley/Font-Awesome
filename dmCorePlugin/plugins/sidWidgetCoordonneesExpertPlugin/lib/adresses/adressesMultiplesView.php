<?php

class adressesMultiplesView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc'
        ));
    }
	
	public function getStylesheets() {
		//on créé un nouveau tableau car c'est un nouveau widget (si c'est une extension utiliser $stylesheets = parent::getStylesheets();)
		$stylesheets = array();
		
		//lien vers le js associé au menu
		$cssLink = sidSPLessCss::getCssPathTemplate(). '/Widgets/AdressesMultiples/AdressesMultiples.css';
		//chargement de la CSS si existante
		if (is_file(sfConfig::get('sf_web_dir') . $cssLink)) $stylesheets[] = $cssLink;
		
		return $stylesheets;
	}

    protected function doRender() {
        $vars = $this->getViewVars();
                
        $adresses = dmDb::table('SidCoordName')
                ->createQuery()
                ->where('is_active = ?',true)
                ->orderBy('siege_social DESC')
                ->execute();
        return $this->getHelper()->renderPartial('adresses', 'multiples', array(
                    'adresses' => $adresses,
                    'titreBloc' => $vars['titreBloc']
                ));
    }

}