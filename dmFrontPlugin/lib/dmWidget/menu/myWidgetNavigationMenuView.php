<?php
/**
 * classe d'override de dmFrontPlugin\lib\dmWidget\menu\dmWidgetNavigationMenuView.php 
 * appelée par dmCorePlugin\data\skeleton\config\dm\widget_types.yml
 */

class myWidgetNavigationMenuView extends dmWidgetNavigationMenuView
{

	//Ajout automatique des classes de dossier
	protected function menuAddDir($currentMenu){
		foreach ($currentMenu as $key => $value) {
			//ajout de la classe css
			if(count($value) > 0){
				$value->liClass("dm_dir");
			}
			//rÃ©cursivitÃ© de la fonction
			$this->menuAddDir($value);
		}
		//retour de la valeur de la fonction
		return $currentMenu;
	}


	//AmÃ©lioration html5 du menu (ajout conteneur nav)
	protected function doRender()
	{
		//insertion de la CSS du widget du theme courant
		use_stylesheet('/theme/css/widgetNavigationMenu.css');

		// use cache if available
		if ($this->isCachable() && $cache = $this->getCache())
		{
		  return $cache;
		}
		
		// get the view vars processed from the form
		$vars = $this->getViewVars();
		
		//on ajoute la classe du type de menu provenant du framework less (Ã  terme overridÃ© dans un paramÃ¨tre du widget)
		$vars['menu']->ulClass(myUser::getLessParam('templateMenu'));

		//lien vers le js associé au menu
		$jsLink = sfConfig::get('sf_js_path_framework') . '/navigationMenu/' . myUser::getLessParam('templateMenu') . '.js';
		//on vérifie que le js existe
		$jsExist = is_file(sfConfig::get('sf_web_dir') . $jsLink);
		//chargement du JS si existant
		if($jsExist) use_javascript($jsLink);

		//INSERTION EN DUR DU SCRIPT ACCORDION POUR TENOR
		if(myUser::getLessParam('mainTemplate') == 'BaseTheme'){
			use_javascript(sfConfig::get('sf_js_path_framework') . '/navigationMenu/accordion.js');
		}

		//ajout des classes CSS de dossier
		$vars['menu'] = $this->menuAddDir($vars['menu']);

		$html = $this->getHelper()->tag('nav', $vars['menu']->render());
		
		//$html.= 'TEST menu : '.myUser::getLessParam('templateMenu');
		/*
		$html = '';
		$counter = 0;
		foreach ($vars['menu'] as $key => $value) {
				$html.="&nbsp;&nbsp;&nbsp;&nbsp;".$counter." ".$key." => ".$value."<br/>";
				//echo $key.':			'.$value;
				//echo "\n";

				$counter++;
		}
		*/
		
		// cache the HTML
		if ($this->isCachable())
		{
		  $this->setCache($html);
		}
		
		return $html;
	}
}