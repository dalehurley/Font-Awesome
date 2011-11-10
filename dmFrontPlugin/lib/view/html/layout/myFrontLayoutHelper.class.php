<?php
/**
 * 
 * classe d'override de dmFrontPlugin\lib\view\html\layout\dmFrontLayoutHelper.php 
 * appelée par dmCorePlugin\data\skeleton\apps\front\config\dm\services.yml
 * 
 */

class myFrontLayoutHelper extends dmFrontLayoutHelper {
	
	//INCLURE ICI LES FONCTION OVERRIDE DE dmCoreLayoutHelper
	public static $testFrontVart= "superTest";

	//Modification de l'ordre des appels dans le head
	public function renderHead()
	{
		return
		$this->renderHttpMetas().
		$this->renderMetas().
		$this->renderFavicon().
		$this->renderTouchIcon().
		$this->renderStylesheets().
		$this->renderHeadJavascripts().
		$this->renderIeHtml5Fix();
	}

	//Meilleure gestion du tag html pour la gestion des diffÃ©rentes versions de IE
	public function renderHtmlTag()
	{
		$culture = $this->serviceContainer->getParameter('user.culture');

		if ($this->isHtml5() || $this->isHtml4)
		{
			$htmlTag = sprintf('<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="%s"> <![endif]-->', $culture)."\n".
					   sprintf('<!--[if IE 7]> <html class="no-js ie7 oldie" lang="%s"> <![endif]-->', $culture)."\n".
					   sprintf('<!--[if IE 8]> <html class="no-js ie8 oldie" lang="%s"> <![endif]-->', $culture)."\n".
					   sprintf('<!--[if IE 9]> <html class="no-js ie9 oldie" lang="%s"> <![endif]-->', $culture)."\n".
					   sprintf('<!--[if gt IE 9]><!--> <html class="no-js" lang="%s"> <!--<![endif]-->', $culture);
		}
		else
		{
			$htmlTag = sprintf(
				'<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="%s"%s >',
				$culture,
				'1.1' == $this->getDocTypeOption('version', '1.0') ? '' : " lang=\"$culture\""
			);
		}

		return $htmlTag;
	}

	//Ajout de la variable de gabarit dans les classes CSS appelÃ©es dans le body (par Lionel)
	public function renderBodyTag($options = array()) {
        $options = dmString::toArray($options);
		
        if ($this->page->get('gabarit') == '' || $this->page->get('gabarit') == 'default') {
            //$bodyClass = 'default';
			$bodyClass = myUser::getLessParam('templateGabarit');
		} else {
            $bodyClass = $this->page->get('gabarit');
        }
        $options['class'][] = $bodyClass;

        return parent::renderBodyTag($options);
    }

	//Modification pour gestion meta charset
	public function renderHttpMetas()
	  {
		$httpMetas = $this->getService('response')->getHttpMetas();

		$html = '';

		foreach($httpMetas as $httpequiv => $value)
		{
			if('Content-Type' === $httpequiv)
			{
				$html .= '<meta charset="utf-8" />'."\n";
			}
			else
			{
				$html .= '<meta http-equiv="'.$httpequiv.'" content="'.$value.'" />'."\n";
			}
		}

		return $html;
	}

	//Simplification html5 de l'intÃ©gration des feuilles de style (suppression de 'type="text/css"')
	public function renderStylesheets()
	{
		/*
		 * Allow listeners of dm.layout.filter_stylesheets event
		 * to filter and modify the stylesheets list
		 */
		$stylesheets = $this->dispatcher->filter(
			new sfEvent($this, 'dm.layout.filter_stylesheets'),
			$this->getService('response')->getStylesheets()
		)->getReturnValue();

		$relativeUrlRoot = dmArray::get($this->serviceContainer->getParameter('request.context'), 'relative_url_root');

		$html = '';
		foreach ($stylesheets as $file => $options)
		{
			$stylesheetTag = '<link rel="stylesheet" media="'.dmArray::get($options, 'media', 'all').'" href="'.$relativeUrlRoot.$file.'" />';

			if (isset($options['condition']))
			{
				$stylesheetTag = sprintf('<!--[if %s]>%s<![endif]-->', $options['condition'], $stylesheetTag);
			}

			$html .= $stylesheetTag."\n";
		}

		sfConfig::set('symfony.asset.stylesheets_included', true);

		return $html;
	}

	//Simplification html5 de l'intÃ©gration des fichiers JS externe dans le head (suppression de 'type="text/javascript"')
	public function renderHeadJavascripts()
	{
		$javascripts = $this->serviceContainer->getService('response')->getHeadJavascripts();
		if(empty($javascripts))
		{
			return '';
		}

		$relativeUrlRoot = dmArray::get($this->serviceContainer->getParameter('request.context'), 'relative_url_root');

		$html = '';
		foreach($javascripts as $file => $options)
		{
			$html.= '<script src="'.($file{0} === '/' ? $relativeUrlRoot.$file : $file).'"></script>';
			$html.= "\n";
		}

		return $html;
	}

	//Simplification html5 de l'intÃ©gration des fichiers JS externe Ã  la fin du body (suppression de 'type="text/javascript"')
	public function renderJavascripts()
	{
		/*
		 * Allow listeners of dm.layout.filter_javascripts event
		 * to filter and modify the javascripts list
		 */
		$javascripts = $this->dispatcher->filter(
			new sfEvent($this, 'dm.layout.filter_javascripts'),
			$this->serviceContainer->getService('response')->getJavascripts()
			)->getReturnValue();

		sfConfig::set('symfony.asset.javascripts_included', true);

		$relativeUrlRoot = dmArray::get($this->serviceContainer->getParameter('request.context'), 'relative_url_root');

		$html = '';
		foreach ($javascripts as $file => $options)
		{
			if(empty($options['head_inclusion']))
			{
				$html .= '<script src="'.($file{0} === '/' ? $relativeUrlRoot.$file : $file).'"></script>';
			}
		}

		return $html;
	}

	//Remplacement de html5shiv par Modernizr pour l'intégration html5
	/*
	 * Ancien lien : //ajax.cdnjs.com/ajax/libs/modernizr/1.7/modernizr-1.7.min.js
	 *
	 * Configuration de la version 2 de Modernizr
	 * CSS3 : tout activer sauf : Flexible Box Model (flexbox), CSS Generated Content
	 * HTML5 : activer les paramètres suivant : Canvas, Canvas Text, HTML5 Audio, HTML5 Video, Input Attributes, Input Types
	 * Extra : HTML5 Shim/IEPP, Modernizr.load, MQ Polyfill, Media Queries, Add CSS Classes, Modernizr.testStyles(), Modernizr.testProp(), Modernizr.testAllProps(), Modernizr._prefixes, Modernizr._domPrefixes
	 */
	public function renderIeHtml5Fix()
	{
		if ($this->isHtml5())
		{
			$html = '<script src="' . sfConfig::get('sf_js_path_framework').'/modernizr/modernizr-2.0.6.custom.min.js"></script>';
			$html.= "\n";
			$html.= '<script src="' . sfConfig::get('sf_js_path_framework').'/modernizr/polyfills.js"></script>';
			$html.= "\n";
			$html.= '<!--[if (gte IE 6)&(lte IE 8)]><script src="'.sfConfig::get('sf_js_cdn_cdnjs').'/selectivizr/1.0.2/selectivizr-min.js"></script><![endif]-->';
		}
		else
		{
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
						)."\n";
		}
		if ($touchIcon72) {
			$html.= sprintf('<link rel="apple-touch-icon" sizes="72x72" href="%s/%s" />',
						dmArray::get($this->serviceContainer->getParameter('request.context'), 'relative_url_root'),
						$touchIcon72
						)."\n";
		}
		if ($touchIcon114) {
			$html.= sprintf('<link rel="apple-touch-icon" sizes="114x114" href="%s/%s" />',
						dmArray::get($this->serviceContainer->getParameter('request.context'), 'relative_url_root'),
						$touchIcon114
						)."\n";
		}
		
		return $html;
	}

	//Simplification html5 (suppression de 'type="image/x-icon"')
	public function renderFavicon()
	{
		$favicon = $this->getFavicon();

		//création html
		$html = '';

		if ($favicon)
		{
			$html.= sprintf('<link rel="shortcut icon" href="%s/%s" />',
						dmArray::get($this->serviceContainer->getParameter('request.context'), 'relative_url_root'),
						$favicon
						)."\n";
		}
		
		return $html;
	}

	//INCLURE ICI LES FONCTION OVERRIDE DE dmFrontLayoutHelper

	//Modification ordre des metas par dÃ©faut et ajout meta viewport
	protected function getMetas()
	{
		$metas = array(
			'title'			=> dmConfig::get('title_prefix').$this->page->get('title').dmConfig::get('title_suffix'),
			'description'  	=> $this->page->get('description'),
			'language'		=> $this->serviceContainer->getParameter('user.culture')
	    );
		
		//à modifier une fois la mise en prod pour autoriser le zoom (désactiver maximum-scale=1.0, user-scalable=0)
		$metas['viewport'] = 'width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0';

		if (sfConfig::get('dm_seo_use_keywords') && $keywords = $this->page->get('keywords'))
		{
			$metas['keywords'] = $keywords;
		}

		if (!dmConfig::get('site_indexable') || !$this->page->get('is_indexable'))
		{
			$metas['robots'] = 'noindex, nofollow';
		}

		if (dmConfig::get('gwt_key') && $this->page->getNode()->isRoot())
		{
			$metas['google-site-verification'] = dmConfig::get('gwt_key');
		}

		return $metas;
	}

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
					window._gaq = [['_setAccount','".$gaKey."'],['_trackPageview'],['_trackPageLoadTime']];
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