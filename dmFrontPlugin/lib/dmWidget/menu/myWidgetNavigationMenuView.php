<?php

/**
 * classe d'override de dmFrontPlugin\lib\dmWidget\menu\dmWidgetNavigationMenuView.php
 * appelée par dmCorePlugin\config\dm\widget_types.yml
 */
class myWidgetNavigationMenuView extends dmWidgetNavigationMenuView {
	
	public function getStylesheets() {
		$stylesheets = parent::getStylesheets();
		
		//lien vers le js associé au menu
        $cssLink = sfConfig::get('sf_css_path_template'). '/Widgets/NavigationMenu/NavigationMenu.css';
		//chargement de la CSS si existante
        if (is_file(sfConfig::get('sf_web_dir') . $cssLink)) $stylesheets[] = $cssLink;
		
		return $stylesheets;
	}
	
	public function getJavascripts() {
		$javascripts = parent::getJavascripts();
		
		// get the view vars processed from the form
        $vars = $this->getViewVars();
		//lien vers le js associé au menu
        $jsLink = sfConfig::get('sf_js_path_framework') . '/navigationMenu/' . $vars['menuType'] . '.js';
        //chargement du JS si existant
        if (is_file(sfConfig::get('sf_web_dir') . $jsLink)) $javascripts[] = $jsLink;
		
		return $javascripts;
	}
	
	//Modification des variables par défault
	protected function filterViewVars(array $vars = array()) {
		$vars = parent::filterViewVars($vars);
		
		if (!isset($vars['menuType']))
            $vars['menuType'] = "default";

        //on ajoute la classe du type de menu provenant du framework less (Ã  terme overridÃ© dans un paramÃ¨tre du widget)
        //$vars['menu']->ulClass(myUser::getLessParam('templateMenu'));
        $vars['menu']->ulClass($vars['menuType']);
		
		//ajout des classes CSS de dossier
        $vars['menu'] = $this->menuAddDir($vars['menu']);
		
		return $vars;
	}
	
	//Ajout automatique des classes de dossier
    protected function menuAddDir($currentMenu) {
        foreach ($currentMenu as $key => $value) {
            //ajout de la classe css
            if (count($value) > 0) {
                $value->liClass("dm_dir");
            }
            //récursivité de la fonction
            $this->menuAddDir($value);
        }
        //retour de la valeur de la fonction
        return $currentMenu;
    }
	
    //Amelioration html5 du menu (ajout conteneur nav)
    protected function doRender() {
		// use cache if available
        if ($this->isCachable() && $cache = $this->getCache()) {
            return $cache;
        }
		
		//récupération html de base du menu
		$html = parent::doRender();
		//ajout container nav
		$html = $this->getHelper()->tag('nav role="navigation"', $html);
		
		// cache the HTML
        if ($this->isCachable()) {
            $this->setCache($html);
        }
		
        return $html;
    }

}