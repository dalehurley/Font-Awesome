<?php

/**
 * classe d'override de dmFrontPlugin\lib\dmWidget\search\dmWidgetSearchFormView.php
 * appelée par dmCorePlugin\config\dm\widget_types.yml
 */
class myWidgetSearchFormView extends dmWidgetSearchFormView {
	
	public function getStylesheets() {
		$stylesheets = parent::getStylesheets();

		//lien vers le js associé au menu
		$cssLink = '/theme/css/_templates/'.dmConfig::get('site_theme'). '/Widgets/SearchForm/SearchForm.css';
		//chargement de la CSS si existante
		if (is_file(sfConfig::get('sf_web_dir') . $cssLink)) $stylesheets[] = $cssLink;

		return $stylesheets;
	}

	public function getJavascripts() {
		$javascripts = parent::getJavascripts();

		//lien vers le js associé au menu
        $jsLink = '/theme/less/_framework/SPLessCss/Externals/js/polyfills/dmWidgetSearchForm.js';        
        //chargement du JS si existant
        if (is_file(sfConfig::get('sf_web_dir') . $jsLink)) $javascripts[] = $jsLink;
		
		return $javascripts;
	}
}