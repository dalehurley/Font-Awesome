<?php

/**
 * classe d'override de dmFrontPlugin\lib\dmWidget\search\dmWidgetSearchFormView.php
 * appelée par dmCorePlugin\config\dm\widget_types.yml
 */
class myWidgetSearchFormView extends dmWidgetSearchFormView {
	
	public function getStylesheets() {
		$stylesheets = parent::getStylesheets();

		//lien vers le js associé au menu
		$cssLink = sfConfig::get('sf_css_path_template'). '/Widgets/SearchForm/SearchForm.css';
		//chargement de la CSS si existante
		if (is_file(sfConfig::get('sf_web_dir') . $cssLink)) $stylesheets[] = $cssLink;

		return $stylesheets;
	}
}