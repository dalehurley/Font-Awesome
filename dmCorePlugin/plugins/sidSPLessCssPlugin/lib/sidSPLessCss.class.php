<?php

/**
 * class baseEditorialTools
 *
 */
class sidSPLessCss {
	
	//récupère le fichier de variables CSS
	private static function getVariableFileCss() {
		//récupération du chemin vers les CSS
		$cssPath = sfLESS::getConfig()->getCssPaths();	
		return $cssPath . '_framework/SPLessCss/Config/GetVariables.css';
	}
	
	//récupère le fichier de variables JSON
	private static function getVariableFileJson() {
		//récupération du chemin vers les LESS
		$lessPath = sfLESS::getConfig()->getLessPaths();
		return $lessPath . 'GetVariables.json';
	}
	
	//actualisation des paramètres du framework dans un JSON
	public static function loadLessParameters() {
		//tableau de valeurs
		$parameterValue = array();
		
		//ouverture du fichier less à lire
        $lessParserOpen = fopen(self::getVariableFileCss(), "r");
		
		//détection erreur d'ouverture
        if ($lessParserOpen === false) {
            return false;
        } else {
			//lecture ligne par ligne du contenu du fichier
            while (!feof($lessParserOpen)) {
				//contenu ligne courante
                $lessCurrentLine = fgets($lessParserOpen, 4096);
				
				//on vérifie que la ligne contient bien un point virgule (évite la confusion avec les acolades)
				if(strpos($lessCurrentLine, ';')) {
					//position des deux-points
					$indexSep = strpos($lessCurrentLine, ':');
					$indexEnd = strrpos($lessCurrentLine, ';');
					
					//on récupère le nom de la variable
					$varName = substr($lessCurrentLine, 0, $indexSep);
					$varValue = substr($lessCurrentLine, $indexSep + 1, $indexEnd - $indexSep -1);
					
					//on supprime les caractères encadrant du nom de la variable et de sa valeur
					$varName = str_replace(' ', '', $varName);
					$varValue = str_replace('"', '', $varValue);
					
					//suppresion unité en pixel
					$getUnit = substr($varValue, -2);
					if($getUnit == "px") $varValue = substr($varValue, 0, -2);
					
					//conversion en valeur float
					if(is_numeric($varValue)) $varValue = floatval($varValue);
					
					//remplissage du tableau
					$parameterValue[$varName] = $varValue;
				}
			}
		}
		
		//création du sytème de fichier
		$fs = new sfFilesystem();
		
		//ciblage du JSON
		$fileJson = self::getVariableFileJson();
		
		//création du fichier a
		$fs->touch($fileJson);
		$fs->chmod(array($fileJson), 0777);
		
		//écriture dans un fichier des données
		$testPutContentJson = file_put_contents($fileJson, json_encode($parameterValue));
		
		//gestion de l'erreur d'écriture dans le fichier
		if(!$testPutContentJson) return false;
		
		//valeur de retour
		return true;
	}
	
	//fonction permettant de sortir la valeur d'un paramètre less
    public static function getLessParam($variable = null) {
		//ciblage du JSON
		$fileJson = self::getVariableFileJson();
		
		//on vérifie que le fichier existe
		if(is_file($fileJson)) {
			
			//contenu du fichier
			$jsonContent = file_get_contents($fileJson);
			
			//décodage du fichier
			$jsonDecode = json_decode($jsonContent, true);
			
			//on vérifie que la variable demandée existe bien et on retourne la valeur
			if(array_key_exists($variable, $jsonDecode)){
				return $jsonDecode[$variable];
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	
	//chemin vers les js du framework
	public static function getJsPathFramework() {
		return '/theme/less/_framework/SPLessCss/Externals/js';
	}
	
	//chemin vers les js du template
	public static function getJsPathTemplate() {
		return '/theme/less/_templates/' . self::getLessParam('mainTemplate') . '/Externals/js';
	}
	
	//chemin vers les css du framework
	public static function getCssPathFramework() {
		return '/theme/css/_framework/SPLessCss';
	}
	
	//chemin vers les css du template
	public static function getCssPathTemplate() {
		return '/theme/css/_templates/' . self::getLessParam('mainTemplate');
	}
	
	//chemin vers les images du framework
	public static function getImgPathFramework() {
		return '/theme/less/_framework/SPLessCss/Images';
	}
	
	//chemin vers les images du template
	public static function getImgPathFramework() {
		return '/theme/less/_templates/' . self::getLessParam('mainTemplate') . '/Images';
	}
	
	//chemin vers les images du client
	public static function getImgPathClient() {
		return '/theme/images';
	}
}