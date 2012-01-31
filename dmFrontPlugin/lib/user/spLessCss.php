<?php

class spLessCss extends dmFrontUser {
	
	//fonction d'inialisation de la page (permet de centraliser les fonctionns appelées)
	public static function pageInit($optionsCustom = array()) {
		//Récupération des options de la page (avec fusion des options personnalisées)
		$pageOptions = self::pageTemplateGetOptions($optionsCustom);
		
		//action à effectuer uniquement en DEV
		if ($pageOptions['isDev'] || $pageOptions['isLess']) {
			//affichage du widget de DEBUG du framework
			echo dm_get_widget('sidSPLessCss', 'debug', array());
		}
		
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
		// temps d'execution infini
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
		
		// A la fin du traitement on donne accès à tous les fichiers propriété d'apache: chmod 777 sur toute l'arborescence juste créée par le mkdir recursif
		if ($prct >= 100) exec('chmod 777 -R '.sfConfig::get('sf_web_dir'));
		
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
		$urlThemes = sfConfig::get('sf_web_dir') . sfConfig::get('sf_img_path_framework') . '/Sprites';
		$urlThemesClient = sfConfig::get('sf_web_dir') . sfConfig::get('sf_img_path_client') . '/Sprites';
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
		$urlThemesClient = sfConfig::get('sf_web_dir') . sfConfig::get('sf_img_path_client') . '/Sprites';
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
			$urlThemeClient = sfConfig::get('sf_web_dir') . sfConfig::get('sf_img_path_client') . '/Sprites/' . $themeClient;
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
		$urlSpriteFunctions = sfConfig::get('sf_web_dir') . '/theme/less/_SpriteFunctions.less';
		$urlSpriteGenerate = sfConfig::get('sf_web_dir') . '/theme/less/_SpriteGenerate.less';
		
		//Initialisation des fichier
		if($prct == 0) {
			//ajout des données de copyright dans le fichier
			//$headerInfo = "// _SpriteGenerate.less" . PHP_EOL;
			$headerInfo = "// v1.0" . PHP_EOL;
			$headerInfo.= "// Last Updated : " . date('Y-m-d H:i') . PHP_EOL;
			$headerInfo.= "// Copyright : SID Presse" . PHP_EOL;
			$headerInfo.= "// Author : Arnaud GAUDIN" . PHP_EOL . PHP_EOL;
			
			//compositions des headers pour les deux type de fichiers
			$headerInfoFunctions = "// _SpriteFunctions.less" . PHP_EOL . $headerInfo;
			$headerInfoGenerate = "// _SpriteGenerate.less" . PHP_EOL . $headerInfo;
			
			//écriture du fichier (création si inexistant, remplacement dans le cas contraire)
			$testPutContentFunctions = file_put_contents($urlSpriteFunctions, $headerInfoFunctions);
			$testPutContentGenerate = file_put_contents($urlSpriteGenerate, $headerInfoGenerate);
		} else {
			//assemblage en string des déclarations à ajouter dans le fichier pour grouper les écritures
			$lessDefinitionFunctions = "";
			$lessDefinitionGenerate = "";

			//sinon on rajoute à la fin du fichier les définitions passées en paramètre
			foreach ($lessDefinitions as $lessDefinition) {
				$lessDefinitionFunctions.= $lessDefinition['function'] . PHP_EOL;
				$lessDefinitionGenerate.= $lessDefinition['generate'] . PHP_EOL;
			}
			
			//ajout de retours ligne à la fin de la boucle
			$lessDefinitionFunctions.= PHP_EOL;
			$lessDefinitionGenerate.= PHP_EOL;
			
			//écriture dans les fichiers
			$testPutContentFunctions = file_put_contents($urlSpriteFunctions, $lessDefinitionFunctions, FILE_APPEND);
			$testPutContentGenerate = file_put_contents($urlSpriteGenerate, $lessDefinitionGenerate, FILE_APPEND);
		}
		
		//gestion de l'erreur d'écriture dans le fichier
		if(!$testPutContentFunctions) die("spLessCss | spriteLessGenerate : erreur d'écriture du fichier : " . $urlSpriteFunctions);
		if(!$testPutContentGenerate) die("spLessCss | spriteLessGenerate : erreur d'écriture du fichier : " . $urlSpriteGenerate);
	}
	
	//génération d'un appel LESS de la fonction de génération de sprite
	private static function spriteLessDefinition($theme = "Default", $category, $name, $spriteFormat = "L", $offX = 0, $offY = 0) {
		//@sprite-navigation-test-S() { @spriteDefinition("Default"; "navigation"; "home"; "S"; @spriteFormat_S; 0; 0); }
		//.sprite-navigation-test-S { @sprite-navigation-test-S(); }
		
		//composition du nom de la sprite
		$nomSprite = '';
		if($theme != "Default") $nomSprite.= '-' . $theme;
		$nomSprite.= '-' . $category;
		$nomSprite.= '-' . $name;
		$nomSprite.= '-' . $spriteFormat;
		
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
		
		//ouverture fonction LESS
		$appelFoncBgp = '@spriteBgp(';
		//ajout paramètres
		$appelFoncBgp.= '@spriteFormat_' . $spriteFormat;
		$appelFoncBgp.= '; ' . $offX;
		$appelFoncBgp.= '; ' . $offY;
		//fermeture fonction LESS
		$appelFoncBgp.= ');';
		
		//composition du tableau de sortie
		$output['function'] = '@sprite' . $nomSprite . '() { ' . $appelFoncDef . ' }' . PHP_EOL . '@spriteBgp' . $nomSprite . '() { ' . $appelFoncBgp . ' }';
		$output['generate'] = '.sprite' . $nomSprite . ' { ' . '@sprite' . $nomSprite . '(); }';
		
		//retour du tableau de valeurs
		return $output;
	}
	
	//copie de toutes les icônes et changement des couleurs
	private static function spriteGenerate($hashMd5 = null, $spriteListing = array(), $spriteFormat = 'L'){
		//dimension par défaut des sprites (imposé par le format SVG utilisé)
		$dimDefault = intval(self::getLessParam('spriteFormat'));
		//dimension des sprites dans les paramètres du framework (égale à S, M, L ou X)
		$dimFramework = intval(self::getLessParam('spriteFormat_' . $spriteFormat));
		
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
			$urlThemes = sfConfig::get('sf_web_dir') . sfConfig::get('sf_img_path_client') . '/Sprites';
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
					$isMatchColors = preg_match_all('/\#\#spriteCouleur\d[A-Za-z]*\#\#/', file_get_contents($value['urlClient']), $matchColors);
					
					//on parcourt les couleurs trouvées et on les remplace
					foreach ($matchColors[0] as $colorToken) {
						//on enlève les délimiteurs ## de la couleur
						$colorKey = substr($colorToken, 2, -2);
						//on récupère la valeur de la couleur correspondante
						$colorValue = self::getLessParam($colorKey);
						
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
	
	//récupération du layout par défaut du template sélectionné
	public static function pageSuccessTemplateInclude() {
		//Ciblage du layout de page par défaut du template sélectionné
		$pageSuccessTemplateInclude = sfConfig::get('dm_core_dir') . '/../themesFmk/_templates/' . self::getLessParam('mainTemplate') . '/Externals/php/layouts/pageSuccessTemplate.php';
		
		//on retourne un tableau contenant 3 clefs
		$includeInfo = array(
							'isFile'	=>	is_file($pageSuccessTemplateInclude),
							'errorMsg'	=>	'Le fichier "' . $pageSuccessTemplateInclude . '" est introuvable',
							'include'	=>	$pageSuccessTemplateInclude
						);
		
		return $includeInfo;
	}
	
	//options de page par défaut
	public static function pageTemplateGetOptions($optionsCustom = array()) {
		
		//récupération du gabarit de la page
		$currentGabarit = sfContext::getInstance()->getPage()->get('gabarit');
		if ($currentGabarit == 'default' || $currentGabarit == '') {
			$currentGabarit = self::getLessParam('templateGabarit');
		}
		
		//composition des options de page par défault
		$pageTemplateOptionsDefault = array(
							'isDev'				=> ((sfConfig::get('sf_environment') == 'dev') ? true : false),
							'isLess'			=> ((sfConfig::get('sf_environment') == 'less') ? true : false),
							'currentGabarit'	=> $currentGabarit,
							'areas'				=> array(
								'dm_page_content'		=>	array(
														'areaName'	=> 'content',
														'isActive'	=> true,
														'isPage'	=> true,
														'clearfix'	=> false
													),
								'dm_sidebar_left'	=>	array(
														'areaName'	=> 'left',
														'isActive'	=> (($currentGabarit == 'two-sidebars' || $currentGabarit == 'sidebar-left') ? true : false),
														'isPage'	=> false,
														'clearfix'	=> false
													),
								'dm_sidebar_right'	=>	array(
														'areaName'	=> 'right',
														'isActive'	=> (($currentGabarit == 'two-sidebars' || $currentGabarit == 'sidebar-right') ? true : false),
														'isPage'	=> false,
														'clearfix'	=> false
													)
												)
							);
		
		//on fusionne des éventuelles propriétés personnalisées injectées dans la fonction
		$pageOptions = (count($optionsCustom) === 0) ? $pageTemplateOptionsDefault : self::pageTemplateCustomOptions($pageTemplateOptionsDefault, $optionsCustom);
		
		//retour de la valeur
		return $pageOptions;
	}
	
	//fonction d'insertion de nouvelle Area dans le pageTemplateSuccess
	private static function pageTemplateCustomOptions($options = array(), $customOptions = array()) {
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

	//calcul de la valeur finale
	public static function lessCalculator($str) {
		$fn = create_function("", "return ({$str});" );
		return $fn();
	}

	//fonction bugguée à terminer
	private static function lessIsNumeric($variableValue) {
		//on vérifie directement si ce n'est pas un nombre
		if(is_numeric($variableValue)) return true;
		
		//recherches de chaines de type md5, modifications : jusqu'à la lettre z, ajout majuscules (f normalement)
		$patternMd5 = '/^[0-9a-zA-Z]{7,32}$/';
		$detectisMd5 = preg_match_all($patternMd5, $variableValue, $matches);
		if($detectisMd5 > 0) return false;

		//recherches de chaines de type 2012-01-01T10:25
		$patternDate = '/([0-9]){4}-([0-9]){2}-([0-9]){2}T([0-9]){2}:([0-9]){2}/';
		$detectisDate = preg_match_all($patternDate, $variableValue, $matches);
		if($detectisDate > 0) return false;

		//recherches de chaines de type #ffaa00
		$patternHex = '/^#+(([a-fA-F0-9]){3}){1,2}$/';
		$detectisHex = preg_match_all($patternHex, $variableValue, $matches);
		if($detectisHex > 0) return false;
		
		//recherches de chaines de type chemin URL
		$patternUrl = '/^[^(floor)(round)](http:){0,1}\/+[a-zA-Z]+[\/\w\{\}@]*/';
		$detectisUrl = preg_match_all($patternUrl, $variableValue, $matches);
		if($detectisUrl > 0) return false;
		
		//recherches de chaines de type police de caractères "LucidaGrande,LucidaSansUnicode,LucidaSans,BitstreamVeraSans,sans-serif"
		$patternFont = '/^[^(floor)(round)][a-zA-Z]{1}\w{2,127},*\w*/';
		$detectisFont = preg_match_all($patternFont, $variableValue, $matches);
		if($detectisFont > 0) return false;

		//recherches de chaines de type layout "sidebar-left,sidebar-right,two-sidebars,no-sidebar"
		$patternLayout = '/^[a-z]+\-[a-z]+$/';
		$detectisLayout = preg_match_all($patternLayout, $variableValue, $matches);
		if($detectisLayout > 0) return false;
		
		//recherches de chaines de type CSS ID
		$patternCssId = '/^[a-zA-Z_]+$/';
		$detectisCssId = preg_match_all($patternCssId, $variableValue, $matches);
		if($detectisCssId > 0) return false;
		
		//Ensuite on teste si la valeur est numérique

		//on supprime tout ce qui ne compose pas une opération mathématique (plus toutes les fonctions de Less parsable en php)
		$variableValueCalc = preg_replace("/[^0-9+\-.*\/()(floor)(round)]/", "", $variableValue); 

		//si il ne reste rien c'est que la valeur ne peut pas être numérique
		if ($variableValueCalc == ""){
			return false;
		}else{
			$testEval = eval("\$return=" . $variableValueCalc . ";" );
			
			if($testEval === false) {
				return false;
			}else{
				if(is_int($return)) return true;
				elseif(is_float($return)) return true;
				else return false;
			}
		}
		
		//recherches de chaine de type numérique avec des parenthèses ou des signes mathématique +-*/
		//$patternNumeric = '/[^a-zA-Z][0-9]+[\(\)\-\+\*\.\/]*/';
		//$detectisNumeric = preg_match_all($patternNumeric, $variableValue, $matches);
	}

	//suppression des unités d'une valeur less
	private static function parseLessValue($variableValue) {
		$units=array(
			'em', 'ex', 'px', 'gd', 'rem', 'vw', 'vh', 'vm', 'ch', // Relative length units
			'in', 'cm', 'mm', 'pt', 'pc', // Absolute length units
			'%', // Percentages
			'deg', 'grad', 'rad', 'turn', // Angles
			'ms', 's', // Times
			'Hz', 'kHz', //Frequencies
		);
		
		foreach ($units as $unit) {
			//on évalue l'expression à la recherche de variables existantes
			$pattern = '/[0-9]+'.$unit.'$/';

			$detectUnits = preg_match_all($pattern, $variableValue, $matches, PREG_OFFSET_CAPTURE);

			foreach ($matches[0] as $unitValue) {
				$value = $unitValue[0];
				$lengthValue = strlen($unit) * -1;
				$finalValue = substr($value, 0, $lengthValue);
				$variableValue = str_replace($unitValue[0], $finalValue, $variableValue);
			}
		}

		//on dégage les pourcentages de façon manuelle
		$variableValue = str_replace('%', '', $variableValue);
		
		$detectisNumeric = self::lessIsNumeric($variableValue);
		if($detectisNumeric){
			$variableValue = self::lessCalculator($variableValue);
		}else{
			//À améliorer éventuellement : suppression des crochets dans les string contenant des variables
			//$variableValue = str_replace('{', '', $variableValue);
			//$variableValue = str_replace('}', '', $variableValue);
		}
		
		return $variableValue;
	}

	//évaluation de la valeur d'une variable less
	private static function parseLessVariable($variableValue, $parameterValue = array()) {
		//on évalue l'expression à la recherche de variables existantes
		$patternParam = '/@[\w\{\}]*/';
		$detectSubVariable = preg_match_all($patternParam, $variableValue, $matches, PREG_OFFSET_CAPTURE);
		
		if ($detectSubVariable > 0) {
			
			//echo "pre : ".$variableValue;
			//echo "   |   ";
			//echo " sub   |   ";

			$matchesSorted = array();
			$matchesLengths = array();
			$replace = array();

			//remplissage du tableau de valeurs de match
			foreach ($matches[0] as $value) {
				$matchesSorted[] = $value[0];
			}
			
			//triage du tableau de match en fonction de leurs longueurs
			foreach($matchesSorted as $key => $value){
				$matchesLengths[$key]  = strlen($value);
			}
			array_multisort($matchesLengths, SORT_DESC, SORT_NUMERIC, $matchesSorted);
			//print_r($matchesSorted);

			//remplacement des valeurs
			foreach ($matchesSorted as $value) {
				//suppression des crochets dans les string contenant des variables
				$valueTemp = str_replace('{', '', $value);
				$valueTemp = str_replace('}', '', $valueTemp);
				
				//récupération de la valeur
				$replace = $parameterValue['variable'][substr($valueTemp,1)];
				
				$variableValue = str_replace($value, $replace, $variableValue);
				
				//on vérifie si la valeur contient à nouveau des valeurs à remplacer
				$detectSubVariableRecursive = preg_match_all($patternParam, $variableValue, $matchesRecursive, PREG_OFFSET_CAPTURE);
				
				if($detectSubVariableRecursive > 0){
					//on relance la fonction de façon récursive
					$variableValue = self::parseLessVariable($variableValue, $parameterValue);
				}
			}	
		}
		//on supprime toutes les unités
		$variableValue = self::parseLessValue($variableValue);

		return $variableValue;
	}

    //AJOUT DE FONCTION PERSONNALISEES ICI DANS LE DOUTE (oÃ¹ les mettre dans le modÃ¨le MVC ?)
    public static function loadLessParameters($options = array(), $parameterValue = array()) {
        //on détecte que le paramÃ¨tre lessFile est présent sinon on quitte la fonction
        if ($options['lessFile'] == '') {
            //$options['type'] == '' || 
            return false;
        }

        //Parser de la config du theme choisi
        //ouverture du fichier less à lire
        $lessParserOpen = fopen($options['lessFile'], "r");

        //détection erreur d'ouverture
        if ($lessParserOpen === false) {
            return false;
        } else {
            //initialisation du compteur de ligne
            $lessCounter = 0;

            //lecture ligne par ligne du contenu du fichier
            while (!feof($lessParserOpen)) {
                //incrémentation du compteur de ligne
                $lessCounter++;

                //contenu ligne courante
                $lessCurrentLine = fgets($lessParserOpen, 4096);

                //déclaration des variables
                $lessParserParamName = '';
                $lessParserParamValue = '';

                //on détecte de quel type d'import il s'agit (variable ou import)
                $patternDetectVariable = '/^@[A-Za-z0-9_ ]*:/';
                $patternDetectImport = '/^@import /';

                //on vérifie s'il s'agit d'une ligne de variable ou d'import
                $detectVariable = preg_match($patternDetectVariable, $lessCurrentLine, $lessParserMatchParamName, PREG_OFFSET_CAPTURE);
                $detectImport = preg_match($patternDetectImport, $lessCurrentLine);

                //affection nom de la variable
                if ($detectVariable > 0) {
                    $lessParserParamName = $lessParserMatchParamName[0][0];
                } elseif ($detectImport > 0) {
                    $lessParserParamName = '@import';
                }

                //récupération de la valeur
                //position du début de la valeur
                $offsetVarStart = strlen($lessParserParamName);
                //position du point virgule dans la ligne courante
                $offsetVarEnd = strpos($lessCurrentLine, ';');

                //on vérifie que le point virgule est bien présent
                if (!($offsetVarEnd === false)) {
                    $lessParserParamValue = substr($lessCurrentLine, $offsetVarStart, $offsetVarEnd - $offsetVarStart);
                }

                //on supprime les caractÃ¨res encadrant du nom de la variable et de sa valeur
                $lessParserParamName = preg_replace('/@* *:*/', '', $lessParserParamName);
                $lessParserParamValue = preg_replace('/"*\'* */', '', $lessParserParamValue);

                //affection de l'option
                if ($detectVariable > 0) {
                    $options['type'] = 'variable';
					
					$lessParserParamValue = self::parseLessVariable($lessParserParamValue, $parameterValue);
					
                    //remplissage du tableau de valeurs
                    $parameterValue['variable'][$lessParserParamName] = $lessParserParamValue;
                } elseif ($detectImport > 0) {
                    $options['type'] = 'import';

                    //on supprime les espace en cas d'import
                    $lessParserParamValue = preg_replace('/ */', '', $lessParserParamValue);

                    //composition de l'URL totale
                    $compoImportURL = sfConfig::get('sf_web_dir') . '/theme/less/' . $lessParserParamValue;

                    //appel récursif de la fonction
                    $parameterValue = self::loadLessParameters(array(
                                //'type'		=> 'import',
                                'lessFile' => $compoImportURL
                                    ), $parameterValue
                    );
                }
            }
            //fermeture du fichier
            fclose($lessParserOpen);

            //retour du tableau de valeurs
            return $parameterValue;
        }
    }

    //fonction permettant de sortir la valeur d'un paramÃ¨tre less
    public static function getLessParam($variable = '') {

    	// lioshi : utilisation d'un fichier varsLess pour stocker les vars
    	// @TODO : remplir ce fichier lors de la compilation LESS
    	$tabVars = file_get_contents('/data/www/_lib/diem/varsLess');
    	$lessVariableImport = json_decode($tabVars);
    	return $lessVariableImport->{$variable};




        $lessInitURL = sfConfig::get('sf_web_dir') . "/theme/less/_framework/SPLessCss/Config/_ConfigInit.less";
        $lessVariableImport = self::loadLessParameters(array(
                    'lessFile' => $lessInitURL
                ));

        return $lessVariableImport['variable'][$variable];
    }

    //fonction permettant d'importer la liste des imports des config du framework
    public static function printLessParams() {

        $lessInitURL = sfConfig::get('sf_web_dir') . "/theme/less/_framework/SPLessCss/Config/_ConfigInit.less";

        $lessVariableImport = self::loadLessParameters(array(
                    'lessFile' => $lessInitURL
                ));

        if (!($lessVariableImport === false)) {
            $counter = 0;
            foreach ($lessVariableImport['variable'] as $key => $value) {
                echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $counter . " " . $key . " => " . $value . "<br/>";
                $counter++;
            }
        }
    }

	//calcul de la largeur d'un élément en fonction du nombre de colonnes passées en paramètre
	public static function gridGetWidth($nbreCols = 1, $padSub = 0){
		//récupération des valeurs de dimension de la grille
		$nbreCols = intval($nbreCols);
		$padSub = intval($padSub);
		$gridColWidth = intval(self::getLessParam('gridColWidth'));
		$gridGutter = intval(self::getLessParam('gridGutter'));
		//calcul des paramètres
		$elementWidth = $gridColWidth * $nbreCols + $gridGutter * ($nbreCols -1) - $padSub;

		return $elementWidth;
	}

	//calcul de la hauteur d'un élément en fonction du nombre de lignes passées en paramètre
	public static function gridGetHeight($nbreLine = 1, $padSub = 0) {
		//récupération des valeurs de dimension de la grille
		$nbreLine = intval($nbreLine);
		$padSub = intval($padSub);
		$gridBaseline = intval(self::getLessParam('gridBaseline'));
		//calcul des paramètres
		$elementHeight = ($gridBaseline * $nbreLine) - $padSub;

		return $elementHeight;
	}
	
	//calcul de la largeur du contenu
	public static function gridGetContentWidth(){
		//récupération du gabarit courant
		$currentGabarit = sfContext::getInstance()->getPage()->get('gabarit');
		if ($currentGabarit == 'default' || $currentGabarit == '') {
			$currentGabarit = self::getLessParam('templateGabarit');
		}

		//récupération des valeurs de colonnes
		$gridCol = self::lessCalculator(self::getLessParam('gridCol'));
		$gridCol_SidebarLeft = self::lessCalculator(self::getLessParam('gridCol_SidebarLeft'));
		$gridCol_SidebarRight = self::lessCalculator(self::getLessParam('gridCol_SidebarRight'));
		
		if($currentGabarit === 'sidebar-left'){
			$gridCol_Content = $gridCol - $gridCol_SidebarLeft;
		}elseif($currentGabarit === 'sidebar-right'){
			$gridCol_Content = $gridCol - $gridCol_SidebarRight;
		}elseif($currentGabarit === 'two-sidebars'){
			$gridCol_Content = $gridCol - $gridCol_SidebarLeft - $gridCol_SidebarRight;
		}elseif($currentGabarit === 'no-sidebar'){
			$gridCol_Content = $gridCol;
		}else{
			$gridCol_Content = $gridCol;
		}
		//calcul de la dimension du contenu
		$contentWidth = self::gridGetWidth($gridCol_Content);
		
		return $contentWidth;
	}

	//permet de supprimer les paragraphes
	public static function textEditorStripParagraph($inputText) {
		$filteredText = str_replace('<p>', '', $inputText);
		$filteredText = str_replace('</p>', '', $filteredText);
		return $filteredText;
	}
}