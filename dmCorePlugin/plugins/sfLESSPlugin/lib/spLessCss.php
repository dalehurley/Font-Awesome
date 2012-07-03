<?php

class spLessCss {
	
	//fonction d'inialisation de la page (permet de centraliser les fonctionns appelées)
	public static function pageInit($optionsCustom = array()) {
		//Récupération des options de la page (avec fusion des options personnalisées)
		$pageOptions = self::pageTemplateGetOptions($optionsCustom);
		
		//action à effectuer uniquement en DEV
		if ($pageOptions['isDev'] || $pageOptions['isLess']) {
			//affichage du widget de DEBUG du framework
			echo dm_get_widget('debug', 'debug', array());
		}

		//lien vers le js du framework
		$jsLinkFramework = '/theme/less/_framework/SPLessCss/Externals/js/theme/frontFramework.js';
		//chargement du JS si existant
		if (is_file(sfConfig::get('sf_web_dir') . $jsLinkFramework)) use_javascript($jsLinkFramework);
		//lien vers le js associé au theme
		$jsLinkTemplate = '/theme/less/_templates/' . dmConfig::get('site_theme'). '/Externals/js/theme/frontTemplate.js';
		//chargement du JS si existant
		if (is_file(sfConfig::get('sf_web_dir') . $jsLinkTemplate)) use_javascript($jsLinkTemplate);
		
		//actualisation des paramètres du framework
		//if($pageOptions['isLess']) sidSPLessCss::loadLessParameters();
		
		//ajout des variables dans la config générale
		$addVars = sfConfig::add(array('pageOptions' => $pageOptions));
		
		return $pageOptions;
	}
	
	//fonction de génération de hash md5 du timeStamp unix pour changer les appels de sprite
	public static function spriteUpdateHashMd5() {
		//ciblage du fichier de config
		$urlConfigGeneral = sfConfig::get('sf_web_dir') . '/theme/less/_ConfigGeneral.less';
		
		//génération du timeStamp md5 sur 7 chiffres
		$md5TimeStamp = substr(md5(microtime(true)), 0, 7);
		
		//composition de la commmande de remplacement de valeur
		$regexTimeStamp = '^\@mainHashMd5: "\w+";$';
		$replaceTimeStamp = '\@mainHashMd5: "' . $md5TimeStamp . '";';
		$execTimeStamp = "perl -pi -w -e 's/" . $regexTimeStamp . "/". $replaceTimeStamp ."/g;' " . $urlConfigGeneral;
		
		//exécution de la commande
		exec($execTimeStamp);
		
		//retour du hash md5
		return $md5TimeStamp;
	}
	
	//génération de toutes les sprites dans toutes les dimensions
	public static function spriteInit($hashMd5 = null, $spriteFormat = null) {
		// temps d'execution infini (pour le serveur de prod, Lionel)
		set_time_limit(0);

		//Si on est au début de l'action
		if($spriteFormat == null) {
			//génération d'un nouveau hashMd5
			if($hashMd5 == null) $hashMd5 = self::spriteUpdateHashMd5();
			//purge des miniatures
			self::spriteReset();
			//initialisation du fichier de sortie less
			self::spriteLessGenerate(0);
		}
		
		//récupération du listing des sprites
		$spriteListing = self::spriteGetListing($hashMd5);
		
		//on définit le pourcentage d'avancement en fonction de la miniature effectuée
		//on change également la valeur de spriteFormat pour la boucle suivante
		switch ($spriteFormat) {
			case '':
				$spriteFormat = 'S';
				$prct = 25;
				break;
			case 'S':
				$spriteFormat = 'M';
				$prct = 50;
				break;
			case 'M':
				$spriteFormat = 'L';
				$prct = 75;
				break;
			case 'L':
				$spriteFormat = 'X';
				$prct = 100;
				break;
			case 'X':
				//on quite la fonction pour éviter une boucle infinie
				return false;
				break;
			default:
				$prct = 0;
				break;
		}
		
		//génération des sprites à la résolution sélectionnée
		$lessDefinitions = self::spriteGenerate($hashMd5, $spriteListing, $spriteFormat);
		
		//Génération du fichier less de sortie
		self::spriteLessGenerate($prct, $lessDefinitions);
		
		//Renvoi de valeurs pour l'affichage
		return array(
			'hashMd5'			=> $hashMd5,
			'spriteFormat'		=> $spriteFormat,
			'prct'				=> $prct
		);
	}
	
	//génération du listing des icônes
	private static function spriteGetListing($hashMd5 = null) {
		//emplacement et récupération des thèmes de sprites
		$urlThemes = sfConfig::get('sf_web_dir') . '/theme/less/_framework/SPLessCss/Images/Sprites';
		$urlThemesClient = sfConfig::get('sf_web_dir') . '/theme/images/Sprites';
		$getThemes = sfFinder::type('directory')->follow_link()->relative()->in($urlThemes);
		
		//création du tableau de stockage des sprites
		$spriteListing = array();
		
		//on parcourt tous les thèmes repérés
		foreach ($getThemes as $theme) {
			//emplacement et récupération des sprites du thème
			$urlSprites = $urlThemes . '/' . $theme;
			$getSprites = sfFinder::type('file')->name('*.svg')->follow_link()->relative()->in($urlSprites);
			
			
			//emplacement et récupération des sprites assemblées déjà générées
			$urlSpritesClient = $urlThemesClient . '/' . $theme;
			//désactivation car plus utilisé et mal actualisé à cause de la latence du serveur
			//$spriteListing[$theme]['output'] =  sfFinder::type('file')->name($theme . '-*.png')->follow_link()->in($urlThemesClient);
			
			//on parcourt les sprites trouvées dans le theme
			foreach ($getSprites as $sprite) {
				//décomposition du nom categorie-icone.svg
				$decomp = explode('-', substr($sprite, 0, -4));
				
				//on part du modèle suivant :
				//categorie-icone.svg
				$spriteListing[$theme]['categories'][$decomp[0]][$decomp[1]]['urlFramework'] = $urlSprites . '/' . $sprite;
				$spriteListing[$theme]['categories'][$decomp[0]][$decomp[1]]['urlClient'] = $urlSpritesClient . '/' . $hashMd5 . '-' . $sprite;
			}
		}
		
		return $spriteListing;
	}
	
	//purge des miniatures
	private static function spriteReset(){
		//emplacement et récupération des thèmes de sprites
		$urlThemesClient = sfConfig::get('sf_web_dir') . '/theme/images/Sprites';
		$getThemesClient = sfFinder::type('directory')->follow_link()->relative()->in($urlThemesClient);
		
		//récupération des miniatures assemblées déjà générées
		$outputs = sfFinder::type('file')->name('*-*.png')->follow_link()->in($urlThemesClient);
		//suppression des assemblages déjà générés
		foreach ($outputs as $output) {
			if(is_file($output)) {
				$testUnlink = unlink($output);
				//affichage d'un message en cas d'erreur
				if(!$testUnlink) die("spLessCss | spriteInit : erreur de suppression du fichier d'assemblage : " . $output);
			}
		}
		
		//suppression des SVG déjà générés dans chaque thème généré et suppression de ces derniers
		foreach ($getThemesClient as $themeClient) {
			//emplacement et récupération des sprites du thème
			$urlSpritesClient = $urlThemesClient . '/' . $themeClient;
			$getSpritesClient = sfFinder::type('file')->name('*.svg')->follow_link()->relative()->in($urlSpritesClient);
			
			
			//suppression des SVG déjà générés
			foreach ($getSpritesClient as $spriteClient) {
				$urlSpriteClient = $urlSpritesClient . '/' . $spriteClient;
				if(is_file($urlSpriteClient)) {
					$testUnlink = unlink($urlSpriteClient);
					//affichage d'un message en cas d'erreur
					if(!$testUnlink) die("spLessCss | spriteInit : erreur de suppression du fichier SVG : " . $urlSpriteClient);
				}
			}
			
			//suppression du dossier du thème
			$urlThemeClient = sfConfig::get('sf_web_dir') . '/theme/images/Sprites/' . $themeClient;
			if(is_dir($urlThemeClient)){
				$testRmdir = rmdir($urlThemeClient);
				//affichage d'un message en cas d'erreur
				if(!$testRmdir) die("spLessCss | spriteGenerate : erreur de suppression du dossier : " . $urlThemeClient);
			}
		}
	}
	
	//génération des appels de la fonctions less de génération des sprites
	private static function spriteLessGenerate($prct = 0, $lessDefinitions = array()) {
		//chemin vers le fichier de config des sprites
		$urlSpriteVariables = sfConfig::get('sf_web_dir') . '/theme/less/_SpriteVariables.less';
		//$urlSpriteFunctions = sfConfig::get('sf_web_dir') . '/theme/less/_SpriteFunctions.less';
		$urlSpriteGenerate = sfConfig::get('sf_web_dir') . '/theme/less/_SpriteGenerate.less';
		
		//création du system de fichier
		$fs = new sfFilesystem();
		//Assemblage des fichiers dans un tableau
		$fsFiles = array($urlSpriteVariables, $urlSpriteGenerate);
		
		//création des fichiers et chmod
		$fs->touch($fsFiles);
		$fs->chmod($fsFiles, 0777);
		
		//Initialisation des fichier
		if($prct == 0) {
			//ajout des données de copyright dans le fichier
			//$headerInfo = "// _SpriteGenerate.less" . PHP_EOL;
			$headerInfo = "// v1.0" . PHP_EOL;
			$headerInfo.= "// Last Updated : " . date('Y-m-d H:i') . PHP_EOL;
			$headerInfo.= "// Copyright : SID Presse" . PHP_EOL;
			$headerInfo.= "// Author : Arnaud GAUDIN" . PHP_EOL . PHP_EOL;
			
			//compositions des headers pour les deux type de fichiers
			$headerInfoVariables = "// _SpriteVariables.less" . PHP_EOL . $headerInfo;
			$headerInfoFunctions = "// _SpriteFunctions.less" . PHP_EOL . $headerInfo;
			$headerInfoGenerate = "// _SpriteGenerate.less" . PHP_EOL . $headerInfo;
			
			//écriture du fichier (création si inexistant, remplacement dans le cas contraire)
			$testPutContentVariables = file_put_contents($urlSpriteVariables, $headerInfoVariables);
			//$testPutContentFunctions = file_put_contents($urlSpriteFunctions, $headerInfoFunctions);
			$testPutContentGenerate = file_put_contents($urlSpriteGenerate, $headerInfoGenerate);
		} else {
			//assemblage en string des déclarations à ajouter dans le fichier pour grouper les écritures
			$lessDefinitionVariables = "";
			$lessDefinitionFunctions = "";
			$lessDefinitionGenerate = "";

			//sinon on rajoute à la fin du fichier les définitions passées en paramètre
			foreach ($lessDefinitions as $lessDefinition) {
				$lessDefinitionVariables.= $lessDefinition['variable'] . PHP_EOL;
				//$lessDefinitionFunctions.= $lessDefinition['function'] . PHP_EOL;
				$lessDefinitionGenerate.= $lessDefinition['generate'] . PHP_EOL;
			}
			
			//on ne rajoute les variables de positionnement qu'une seule fois, à la fin de la génération
			if($prct >= 100) {
				$testPutContentVariables = file_put_contents($urlSpriteVariables, $lessDefinitionVariables, FILE_APPEND);
				//écriture dans le fichier
				if(!$testPutContentVariables) die("spLessCss | spriteLessGenerate : erreur d'écriture du fichier : " . $urlSpriteVariables);
			}
			
			//ajout de retours ligne à la fin de la boucle
			$lessDefinitionVariables.= PHP_EOL;
			$lessDefinitionFunctions.= PHP_EOL;
			$lessDefinitionGenerate.= PHP_EOL;
			
			//écriture dans les fichiers
			//$testPutContentFunctions = file_put_contents($urlSpriteFunctions, $lessDefinitionFunctions, FILE_APPEND);
			$testPutContentGenerate = file_put_contents($urlSpriteGenerate, $lessDefinitionGenerate, FILE_APPEND);
		}
		
		//gestion de l'erreur d'écriture dans le fichier
		//if(!$testPutContentFunctions) die("spLessCss | spriteLessGenerate : erreur d'écriture du fichier : " . $urlSpriteFunctions);
		if(!$testPutContentGenerate) die("spLessCss | spriteLessGenerate : erreur d'écriture du fichier : " . $urlSpriteGenerate);
	}
	
	//génération d'un appel LESS de la fonction de génération de sprite
	private static function spriteLessDefinition($theme = "Default", $category, $name, $spriteFormat = "L", $offX = 0, $offY = 0) {
		//@sprite-navigation-test-S() { @spriteDefinition("Default"; "navigation"; "home"; "S"; @spriteFormat_S; 0; 0); }
		//.sprite-navigation-test-S { @sprite-navigation-test-S(); }
		
		//composition du nom de la sprite
		$nomSpriteSimple = '';
		if($theme != "Default") $nomSpriteSimple.= '-' . $theme;
		$nomSpriteSimple.= '-' . $category;
		$nomSpriteSimple.= '-' . $name;
		$nomSprite = $nomSpriteSimple . '-' . $spriteFormat;
		
		//ouverture fonction LESS
		$appelFoncDef = '@spriteDefinition(';
		//ajout paramètres
		$appelFoncDef.= '"' . $theme . '"';
		$appelFoncDef.= '; "' . $category . '"';
		$appelFoncDef.= '; "' . $name . '"';
		$appelFoncDef.= '; "' . $spriteFormat . '"';
		$appelFoncDef.= '; @spriteFormat_' . $spriteFormat;
		$appelFoncDef.= '; ' . $offX;
		$appelFoncDef.= '; ' . $offY;
		//fermeture fonction LESS
		$appelFoncDef.= ');';
		/*
		//ouverture fonction LESS
		$appelFoncBgp = '@spriteBgp(';
		//ajout paramètres
		$appelFoncBgp.= '@spriteFormat_' . $spriteFormat;
		$appelFoncBgp.= '; ' . $offX;
		$appelFoncBgp.= '; ' . $offY;
		//fermeture fonction LESS
		$appelFoncBgp.= ');';
		*/
		//composition du tableau de sortie
		$output['variable'] = '@spriteOffX' . $nomSpriteSimple . ':' . $offX . ';' . PHP_EOL . '@spriteOffY' . $nomSpriteSimple . ':' . $offY . ';';
		//$output['function'] = '@sprite' . $nomSprite . '() { ' . $appelFoncDef . ' }' . PHP_EOL . '@spriteBgp' . $nomSprite . '() { ' . $appelFoncBgp . ' }';
		$output['generate'] = '.sprite' . $nomSprite . ' { ' . $appelFoncDef . ' }';
		
		//retour du tableau de valeurs
		return $output;
	}
	
	//copie de toutes les icônes et changement des couleurs
	private static function spriteGenerate($hashMd5 = null, $spriteListing = array(), $spriteFormat = 'L'){
		//dimension par défaut des sprites (imposé par le format SVG utilisé)
		$dimDefault = intval(self::getSpriteParam());
		//dimension des sprites dans les paramètres du framework (égale à S, M, L ou X)
		$dimFramework = intval(self::getSpriteParam($spriteFormat));
		
		//calcul du ratio de redimenssionnement
		$resizeRatio = $dimFramework / $dimDefault;
		
		//calcul de la densité (par défaut résolution de 72dpi)
		$density = 72 * $resizeRatio;
		$execDensity = ($resizeRatio != 1) ? ' -density ' . $density : null;
		
		//génération du code LESS en sortie
		$output = array();
		
		//on parcourt les themes
		foreach ($spriteListing as $theme => $info) {
			//récupération des catégories
			$categories = $info['categories'];
			
			//création du dossier du thème si non présent
			$urlThemes = sfConfig::get('sf_web_dir') . '/theme/images/Sprites';
			$urlThemeClient = $urlThemes . '/' . $theme;
			if(!is_dir($urlThemeClient)){
				$testMkdir = mkdir($urlThemeClient, 0775, true);
				//affichage d'un message en cas d'erreur
				if(!$testMkdir) die("spLessCss | spriteGenerate : erreur de création de l'arborescence de dossiers");
			}
			
			//Requête : préparations des composantes assemblées par la suite
			$execConvert = "convert";
			$execConvertOptions = " -background none";
			$execConvertDensityGeneral = true;
			$execConvertAppend = "";
			
			//variable de placement et de comptage
			$numCat = 0;
			
			//on parcourt les catégories du theme
			foreach ($categories as $category => $sprites) {
				$numSprite = 0;
				
				//Requête : ouverture parenthèse
				$execConvertAppend.= " \(";
				
				//on parcourt les sprites de la catégorie
				foreach ($sprites as $sprite => $value) {
					//composition des commandes à exécuter
					$execCopy = "cp ". $value['urlFramework'] . " " . $value['urlClient'];
					
					//exécution de la commande de copie
					exec($execCopy);
					
					//on récupère toutes les occurences de la couleur spriteCouleur dans le fichier
					$isMatchColors = preg_match_all('/\#\#spriteCouleur[A-Za-z\d]*\#\#/', file_get_contents($value['urlClient']), $matchColors);
					
					//on parcourt les couleurs trouvées et on les remplace
					foreach ($matchColors[0] as $colorToken) {
						//on enlève les délimiteurs ## de la couleur
						$colorKey = substr($colorToken, 2, -2);
						//on récupère la valeur de la couleur correspondante
						$colorValue = self::getColorsParam($colorKey);
						
						//composition de la commmande de changement de couleurs
						$execColor = "perl -pi -w -e 's/" . $colorToken . "/". $colorValue ."/g;' " . $value['urlClient'];
						
						//exécution de la commande
						exec($execColor);
					}
					
					//on récupère les dimensions de l'image dans un tableau
					$spriteInfo = explode('-', exec('identify -format "%w-%h" ' . $value['urlClient']));
					$spriteWidth = $spriteInfo[0];
					$spriteHeight = $spriteInfo[1];
					
					//on vérifie si l'une des dimensions ne correspond pas à la dimenssion de base définie, puis on remultiplie par le ratio général
					$spriteResizeRatio = $dimDefault / max($spriteWidth, $spriteHeight);
					$spriteDensity = 72 * $spriteResizeRatio * $resizeRatio;
					if($spriteResizeRatio != 1) {
						$execSpriteDensity = ' -density ' . $spriteDensity;
						$execConvertAppend.= $execSpriteDensity;
						$execConvertDensityGeneral = false;
					}
					
					//Requête : ajout nom de fichier
					$execConvertAppend.= " " . $value['urlClient'];
					
					//ajout de l'appel LESS à la variable de sortie
					$output[] = self::spriteLessDefinition($theme, $category, $sprite, $spriteFormat, $numSprite, $numCat);
					
					//incrémentation numéro de sprite
					$numSprite++;
				}
				
				//Requête : append horizontal et fermeture parenthèse
				$execConvertAppend.= " +append \)";
				
				//incrémentation numéro de catégorie
				$numCat++;
			}
			
			//Requête : assemblage final
			if($execConvertDensityGeneral) $execConvertOptions.= $execDensity;
			$execConvert.= $execConvertOptions . $execConvertAppend;
			$execConvert.= " -append " . $urlThemes . '/' . $hashMd5 . "-" . $theme . "-" . $spriteFormat . ".png";
			
			//Requête : exécution
			exec($execConvert);
		}
		
		//retour de la valeur de sortie LESS
		return $output;	
	}
	/**
	 * renvoie la taille en px pour chaque format
	 * @param  string $spriteFormat Le format
	 * @return string
	 */
	private static function getSpriteParam($spriteFormat = ''){
		switch ($spriteFormat) {
			case '':
				return '64';
				break;
			case 'S':
				return '16';
				break;
			case 'M':
				return '32';
				break;
			case 'L':
				return '64';
				break;
			case 'X':
				return '128';
				break;															
			default:
				return '64';
				break;
		}
	}

	private static function getColorsParam($colorKey){

		// il faut aller chercher dans le fichier  	
		$fileConfig = sfconfig::get('sf_web_dir').'/theme/css/_templates/' . dmConfig::get('site_theme') . '/Config/GetVariables.css';
		// on lit chaque ligne du fichier pour trouver la chaine $colorKey
		$lines = file($fileConfig);
		foreach ($lines as $lineNumber => $lineContent)
		{
			$posColorKey = strpos($lineContent, $colorKey);
			if ($posColorKey === false) {
			} else {
				return substr($lineContent,strpos($lineContent, '#'),7);
			}
		}
	}

	//récupération du layout par défaut du template sélectionné
	public static function pageSuccessTemplateInclude() {
		//Ciblage du layout de page par défaut du template sélectionné
		$pageSuccessTemplateInclude = dm::getDir() . '/dmCorePlugin/plugins/sfLESSPlugin/data/_templates/' . dmConfig::get('site_theme') . '/Externals/php/layouts/pageSuccessTemplate.php';
		
		//on retourne un tableau contenant 3 clefs
		$includeInfo = array(
							'isFile'	=>	is_file($pageSuccessTemplateInclude),
							'errorMsg'	=>	'Le fichier "' . $pageSuccessTemplateInclude . '" est introuvable...',
							'include'	=>	$pageSuccessTemplateInclude
						);
		
		return $includeInfo;
	}
	
	//options de page par défaut
	private static function pageTemplateGetOptions($optionsCustom = array()) {
		//composition des options de page par défault
		$pageTemplateOptionsDefault = array(
							'isDev'				=> ((sfConfig::get('sf_environment') == 'dev') ? true : false),
							'isLess'			=> ((sfConfig::get('sf_environment') == 'less') ? true : false),
							'areas'				=> array(
								'dm_page_content'		=>	array(
														'areaName'	=> 'content',
														'isActive'	=> true,
														'isPage'	=> true,
														'clearfix'	=> false
													),
								'dm_sidebar_left'	=>	array(
														'areaName'	=> 'left',
														'isActive'	=> true,
														'isPage'	=> false,
														'clearfix'	=> false
													),
								'dm_sidebar_right'	=>	array(
														'areaName'	=> 'right',
														'isActive'	=> true,
														'isPage'	=> false,
														'clearfix'	=> false
													)
												)
							);
		
		//on fusionne des éventuelles propriétés personnalisées injectées dans la fonction
		$pageOptions = (count($optionsCustom) === 0) ? $pageTemplateOptionsDefault : self::pageTemplateCustomOptions($pageTemplateOptionsDefault, $optionsCustom);
		
		//on vérifie les configs pour ajouter le paramètre sdbConfig
		if($pageOptions['areas']['dm_sidebar_left']['isActive'] == true && $pageOptions['areas']['dm_sidebar_right']['isActive'] == true) $sdbConfig = "dm_sdbc_two";
		elseif($pageOptions['areas']['dm_sidebar_left']['isActive'] == true)                                                              $sdbConfig = "dm_sdbc_left";
		elseif($pageOptions['areas']['dm_sidebar_right']['isActive'] == true)                                                             $sdbConfig = "dm_sdbc_right";
		else                                                                                                                              $sdbConfig = "dm_sdbc_none";
		//on ajoute le paramètre
		$pageOptions['sdbConfig'] = $sdbConfig;
		
		//retour de la valeur
		return $pageOptions;
	}
	
	//fonction d'insertion de nouvelle Area dans le pageTemplateSuccess
	public static function pageTemplateCustomOptions($options = array(), $customOptions = array()) {
		//on parcourt toutes les zones à insérer
		foreach ($customOptions['areas'] as $id => $area) {
			//on vérifie si un index est définit pour la zone, sinon on la rajoute à la fin
			if(isset($area['index'])) {
				//récupération de l'index d'insertion
				$insertIndex = $area['index'];
				
				//extraction des portions de Areas se trouvant avant et après l'index
				$firstPart = array_slice($options['areas'], 0, $insertIndex, true);
				$lastPart = array_slice($options['areas'], $insertIndex, (count($options['areas']) - $insertIndex), true);
				
				//assemblage dans un tableau temporaire de la zone à rajouter
				$rajout[$id] = $area;
				
				//assemblage du tout
				$options['areas'] = array_merge($firstPart,$rajout,$lastPart);
				
			}
		}
		//une fois que l'on a vérifié toutes les zones à insérer on fusionne les modifications de zones éventuellement présentes
		$options = array_replace_recursive($options, $customOptions);
		
		return $options;
	}
}