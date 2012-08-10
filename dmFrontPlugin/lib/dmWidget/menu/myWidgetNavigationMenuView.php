<?php

/**
 * classe d'override de dmFrontPlugin\lib\dmWidget\menu\dmWidgetNavigationMenuView.php
 * appelée par dmCorePlugin\config\dm\widget_types.yml
 */
class myWidgetNavigationMenuView extends dmWidgetNavigationMenuView {
	
	public function getStylesheets() {
		$stylesheets = parent::getStylesheets();
		
		//récupération des variables de la vue
		$vars = $this->getViewVars();
		//lien vers les css associées au menu
		if (dmConfig::get('site_theme_version') == 'v1'){
	        $cssLink1 = '/theme/css/_templates/'.dmConfig::get('site_theme').'/Widgets/NavigationMenu/NavigationMenu.css';
			$cssLink2 = '/theme/css/_templates/'.dmConfig::get('site_theme').'/Widgets/NavigationMenu/NavigationMenu.' . $vars['menuType'] . '.css';
			//chargement des CSS si existantes
	        if (is_file(sfConfig::get('sf_web_dir') . $cssLink1)) $stylesheets[] = $cssLink1;
	        if (is_file(sfConfig::get('sf_web_dir') . $cssLink2)) $stylesheets[] = $cssLink2;
		} else {
	  		// no style for menu, inherit BS and Theme
		}
		
		return $stylesheets;
	}
	
	public function getJavascripts() {
		$javascripts = parent::getJavascripts();
		
		//récupération des variables de la vue
        $vars = $this->getViewVars();
		//lien vers le js associé au menu
        //$jsLink = '/theme/less/_templates/'.dmConfig::get('site_theme').'/Externals/js/navigationMenu/' . $vars['menuType'] . '.js';
        if (dmConfig::get('site_theme_version') == 'v1'){
        	$jsLink = '/theme/less/_framework/SPLessCss/Externals/js/navigationMenu/' . $vars['menuType'] . '.js'; 
        } else { 
        	// all suffixed navbar menu type (navbar-top, navbar-bottom...etc) use the same js
        	if (substr($vars['menuType'],0,6) == 'navbar'){
        		$varMenuType = 'navbar';
        	} else {
        		$varMenuType = $vars['menuType'];
        	}
        	
        	if ($varMenuType == 'navbar'){
        		$jsLink[] = '/theme/less/bootstrap/js/bootstrap-dropdown.js';
        		$jsLink[] = '/theme/less/bootstrap/js/bootstrap-collapse.js';
        	}
        }
        //chargement du JS si existant
        //if (is_file(sfConfig::get('sf_web_dir') . $jsLink)) 
        	$javascripts = array_merge($javascripts,$jsLink);
		
		return $javascripts;
	}
	
	//Modification des variables par défault
	protected function filterViewVars(array $vars = array()) {
		$vars = parent::filterViewVars($vars);
		
		//debug menuType qui visiblement ne prend pas la valeur par défaut
		if(!isset($vars['menuType'])) $vars['menuType'] = "default";
		
        //on ajoute la classe du type de menu provenant du paramètre du widget
        $vars['menu']->ulClass('menu-'.$vars['menuType']);
		
		//ajout des classes CSS de dossier
        $vars['menu'] = $this->menuAddClass($vars['menu'], $vars['menuType']);

        // ajout menuName
        $vars['menu']->menuName($vars['menuName']);
		
		return $vars;
	}
	
	//Ajout automatique des classes en fonction du type de menu
    protected function menuAddClass($currentMenu, $menuType) {
    	
    	if (substr($menuType,0,6) == 'navbar'){
        		$menuType = 'navbar';
        } 

    	switch ($menuType) {
    		case 'navbar':
    			$classAdded = 'dropdown';
    			break;
    		
    		default:
    			$classAdded = 'dm_dir';
    			break;
    	}
        
        foreach ($currentMenu as $key => $value) {
            //ajout de la classe css
            if (count($value) > 0) $value->liClass($classAdded);
            //récursivité de la fonction
            $this->menuAddClass($value, $menuType);
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
		//récupération du helper (évite d'appeler la fonction à chaque fois)
		$helper = $this->getHelper();
		//récupération des variables
		$vars = $this->getViewVars();


		// navbars
        if (substr($vars['menuType'],0,6) == 'navbar'){
        	$varMenuType = 'navbar';
        } else {
        	$varMenuType = $vars['menuType'];
        }
        switch ($vars['menuType']) {
        	case 'navbar-top':
        		$addedNavbarClass = '.navbar-fixed-top';
        		break;
        	case 'navbar-bottom':
        		$addedNavbarClass = '.navbar-fixed-bottom';
        		break;        		
        	
        	default:
        		$addedNavbarClass = '';
        		break;
        }

		// ajout balises pour navbar
		if($varMenuType == 'navbar' ){

			// add responsive navbar HTML code
			$responsiveHtmlBegin = '<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</a>
			 
			<a class="brand" href="/">'.$vars['menuName'].'</a>
			 
			<div class="nav-collapse collapse">
			';

			$responsiveHtmlEnd = '</div>';



			$html = $helper->tag('div.navbar'.$addedNavbarClass, 
						$helper->tag('div.navbar-inner',  
							$helper->tag('div.container', $responsiveHtmlBegin.$html.$responsiveHtmlEnd
								)
							)
					);
		}
		
		//génération des paramètres du menu en fonction de son type (à voir pour intégration propre avec chaque type de menu, cf dmWidgetContentNivoGallery pour méthode)
		$menuParam = array();
		if($vars['menuType'] === 'accordion' ){
			$menuParam = array('json' => array(
											'duration'		=> 250,
											'easing'		=> 'swing'
											));
		}
		
		//ajout container nav avec options par défault
		$html = $helper->tag('nav.dm_widget_navigation_menu_container role="navigation"', $menuParam, $html);
		
		// cache the HTML
        if ($this->isCachable()) {
            $this->setCache($html);
        }
		
        return $html;
    }

}