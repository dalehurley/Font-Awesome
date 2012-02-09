<?php
/**
 * 
 * classe d'override de dmFrontPlugin\lib\view\html\layout\dmFrontLayoutHelper.php 
 * appelée par dmCorePlugin\data\skeleton\apps\front\config\dm\services.yml
 * 
 */

class myFrontLayoutHelper extends dmFrontLayoutHelper {
	
	//INCLURE ICI LES FONCTION OVERRIDE DE dmCoreLayoutHelper

	//Modification de l'ordre des appels dans le head
	public function renderHead()
	{
		return
		$this->renderHttpMetas().
		$this->renderMetas().
		$this->renderXmlNsHeadTags().
		$this->renderStylesheets().		
		$this->renderFavicon().
		$this->renderTouchIcon().
		$this->renderIeHtml5Fix().
		$this->renderHeadJavascripts();
	}
	
	//Ajout de la variable de gabarit dans les classes CSS appelÃ©es dans le body (par Lionel)
	public function renderBodyTag($options = array()) {
        $options = dmString::toArray($options);
		
		//récupération des options de la page
		$pageOptions = sfConfig::get('pageOptions');
		
        //ajout des classes personnalisée sur le body
        $options['class'][] = $pageOptions['currentGabarit'];
		if($pageOptions['isDev']) $options['class'][] = 'isDev';
		if($pageOptions['isLess']) $options['class'][] = 'isLess';
		
        return parent::renderBodyTag($options);
    }
	
	//Remplacement de html5shiv par Modernizr pour l'intégration html5
	/*
	 * Ancien lien : //ajax.cdnjs.com/ajax/libs/modernizr/1.7/modernizr-1.7.min.js
	 *
	 * Configuration de la version 2 de Modernizr
	 * CSS3 : tout activer sauf : Flexible Box Model (flexbox)
	 * HTML5 : activer les paramètres suivant : Canvas, Canvas Text, HTML5 Audio, HTML5 Video, Input Attributes, Input Types
	 * Misc : activer Inline SVG, SVG, SVG clip path, Touch Events
	 * Extra : HTML5 Shim/IEPP, Modernizr.load, MQ Polyfill, Media Queries, Add CSS Classes, Modernizr.testStyles(), Modernizr.testProp(), Modernizr.testAllProps(), Modernizr._prefixes, Modernizr._domPrefixes
	 * Community add-ons : tout activer en attendant filtrage nécessaire
	 */
	public function renderIeHtml5Fix()
	{
		if ($this->isHtml5()) {
			$html = '<script src="' . sidSPLessCss::getJsPathFramework().'/modernizr/modernizr-2.0.6.custom.min.js"></script>';
			$html.= PHP_EOL;
			$html.= '<!--[if (gte IE 6)&(lte IE 8)]><script src="'.sfConfig::get('sf_js_cdn_cdnjs').'/selectivizr/1.0.2/selectivizr-min.js"></script><![endif]-->';
		} else {
			$html = '';
		}
		return $html;
	}

	//Rajout fonction personnalisée pour avoir l'icone Apple
	protected function getTouchIcon($size = '')
	{
		foreach(array('ico', 'png', 'gif') as $extension)
		{
			//composition du nom du fichier
			if($size != ''){
				$appleIcon = sfConfig::get('sf_web_dir').'/apple-touch-icon-'.$size.'-precomposed.'.$extension;
			}else{
				$appleIcon = sfConfig::get('sf_web_dir').'/apple-touch-icon-precomposed.'.$extension;
			}
			
			if (file_exists($appleIcon)) {
				return $appleIcon;
			}
		}
	}
	
	//Gestion des icônes pour les appareils mobiles
	public function renderTouchIcon() {
		$touchIcon = $this->getTouchIcon();
		$touchIcon72 = $this->getTouchIcon('72x72');
		$touchIcon114 = $this->getTouchIcon('114x114');
		
		//création html
		$html = '';
		
		//rajout des icones précomposées à différentes résolutions
		if ($touchIcon) {
			$html.= sprintf('<link rel="apple-touch-icon" href="%s/%s" />',
						dmArray::get($this->serviceContainer->getParameter('request.context'), 'relative_url_root'),
						$touchIcon
						);
			$html.= PHP_EOL;
		}
		if ($touchIcon72) {
			$html.= sprintf('<link rel="apple-touch-icon" sizes="72x72" href="%s/%s" />',
						dmArray::get($this->serviceContainer->getParameter('request.context'), 'relative_url_root'),
						$touchIcon72
						);
			$html.= PHP_EOL;
		}
		if ($touchIcon114) {
			$html.= sprintf('<link rel="apple-touch-icon" sizes="114x114" href="%s/%s" />',
						dmArray::get($this->serviceContainer->getParameter('request.context'), 'relative_url_root'),
						$touchIcon114
						);
			$html.= PHP_EOL;
		}
		
		return $html;
	}

	//INCLURE ICI LES FONCTION OVERRIDE DE dmFrontLayoutHelper
	
	//Optimisation intégration code Google Analytics, à  mettre à jour en fonction des dernières optimisations :
	//mathiasbynens.be/notes/async-analytics-snippet
	protected function getGoogleAnalyticsCode($gaKey)
	{
		//utilisation de la version optimisée de 293 octets
		/*
		$html = "<script>
					var _gaq = [['_setAccount', '".$gaKey."'], ['_trackPageview']];
					(function(d, t) {
						var g = d.createElement(t),
							s = d.getElementsByTagName(t)[0];
						g.async = 1;
						g.src = '//www.google-analytics.com/ga.js';
						s.parentNode.insertBefore(g, s);
					}(document, 'script'));
				</script>";*/
		//Remplacement par la version optimisée avec Modernizr.load
		$html = "<script>
					window._gaq = [['_setAccount','".$gaKey."'],['_trackPageview']];
					Modernizr.load({
						load: ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js'
					});
				</script>";
		
		return $html;
	}
	
	//Propose l'installation de Chrome Frame pour les utilisateurs de IE6 sans droit administrateur (nécessaire pour cause d'arrêt du support d'IE6)
	//chromium.org/developers/how-tos/chrome-frame-getting-started
	public function renderChromeFrame() {
		$html = "<!--[if lt IE 7 ]>
					<script src=\"//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js\"></script>
					<script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
				<![endif]-->";
		return $html;
	}
}