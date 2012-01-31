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
			//initialisation du compteur de ligne
            $lessCounter = 0;
			
			//lecture ligne par ligne du contenu du fichier
            while (!feof($lessParserOpen)) {
				//incrémentation du compteur de ligne
                $lessCounter++;
				
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
					
					//remplissage du tableau
					$parameterValue[$varName] = $varValue;
				}
			}
		}
		
		//création du sytème de fichier
		$fs = new sfFilesystem();
		
		//création du fichier
		$fileJson = self::getVariableFileJson();
		
		$fs->touch($fileJson);
		$fs->chmod(array($fileJson), 0777);
		
		//écriture dans un fichier des données
		$testPutContentJson = file_put_contents($fileJson, json_encode($parameterValue));
		
		//gestion de l'erreur d'écriture dans le fichier
		if(!$testPutContentJson) return false;
		
		//valeur de retour
		return true;
	}
	
	//fonction permettant de sortir la valeur d'un paramÃ¨tre less
    public static function getLessParam($variable = null) {
		
		
		
		
		
	}
}