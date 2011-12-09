<?php

class spLessCss extends dmFrontUser {
	
	
	
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

	//calcul de la valeur finale
	public static function lessCalculator($str) {
		$fn = create_function("", "return ({$str});" );
		return $fn();
	}

	//fonction bugguée à terminer
	private static function lessIsNumeric($variableValue) {
		$patternHex = '/^#+(([a-fA-F0-9]){3}){1,2}$/';
		$detectisHex = preg_match_all($patternHex, $variableValue, $matchesNumeric);

		$patternDate = '/([0-9]){4}-([0-9]){2}-([0-9]){2}T([0-9]){2}:([0-9]){2}/';
		$detectisDate = preg_match_all($patternDate, $variableValue, $matchesNumeric);

		//valeur de retour initiale
		$returnValue = 0;

		if($detectisDate > 0) {
			$returnValue = 0;
		}elseif($detectisHex > 0) {
			$returnValue = 0;
		}else{
			$patternNumeric = '/[0-9]+[\(\)\-\+\*\.]*/';
			$detectisNumeric = preg_match_all($patternNumeric, $variableValue, $matchesNumeric);

			if($detectisNumeric > 0){
				$returnValue = 1;
			}
		}
		
		return $returnValue;
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

		$detectisNumeric = spLessCss::lessIsNumeric($variableValue);
		if($detectisNumeric>0){
			$variableValue = spLessCss::lessCalculator($variableValue);
		}else{
			//À améliorer éventuellement : suppression des crochets dans les string contenant des variables
			$variableValue = str_replace('{', '', $variableValue);
			$variableValue = str_replace('}', '', $variableValue);
		}
		
		return $variableValue;
	}

	//évaluation de la valeur d'une variable less
	private static function parseLessVariable($variableValue, $parameterValue = array()) {
		//on évalue l'expression à la recherche de variables existantes
		$patternParam = '/@[A-Za-z0-9_]*/';

		$detectSubVariable = preg_match_all($patternParam, $variableValue, $matches, PREG_OFFSET_CAPTURE);
		
		if ($detectSubVariable > 0) {

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
				$replace = $parameterValue['variable'][substr($value,1)];

				$variableValue = str_replace($value, $replace, $variableValue);
				
				//on vérifie si la valeur contient à nouveau des valeurs à remplacer
				$detectSubVariableRecursive = preg_match_all($patternParam, $variableValue, $matchesRecursive, PREG_OFFSET_CAPTURE);
				
				if($detectSubVariableRecursive > 0){
					//on relance la fonction de façon récursive
					$variableValue = spLessCss::parseLessVariable($variableValue, $parameterValue);
				}
			}
		}
		//on supprime toutes les unités
		$variableValue = spLessCss::parseLessValue($variableValue);

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
					
					$lessParserParamValue = spLessCss::parseLessVariable($lessParserParamValue, $parameterValue);
					
                    //remplissage du tableau de valeurs
                    $parameterValue['variable'][$lessParserParamName] = $lessParserParamValue;
                } elseif ($detectImport > 0) {
                    $options['type'] = 'import';

                    //on supprime les espace en cas d'import
                    $lessParserParamValue = preg_replace('/ */', '', $lessParserParamValue);

                    //composition de l'URL totale
                    $compoImportURL = sfConfig::get('sf_web_dir') . '/theme/less/' . $lessParserParamValue;

                    //appel récursif de la fonction
                    $parameterValue = spLessCss::loadLessParameters(array(
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
        $lessInitURL = sfConfig::get('sf_web_dir') . "/theme/less/_framework/SPLessCss/Config/_ConfigInit.less";
        $lessVariableImport = spLessCss::loadLessParameters(array(
                    'lessFile' => $lessInitURL
                ));

        return $lessVariableImport['variable'][$variable];
    }

    //fonction permettant d'importer la liste des imports des config du framework
    public static function printLessParams() {

        $lessInitURL = sfConfig::get('sf_web_dir') . "/theme/less/_framework/SPLessCss/Config/_ConfigInit.less";

        $lessVariableImport = spLessCss::loadLessParameters(array(
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
		$gridColWidth = intval(spLessCss::getLessParam('gridColWidth'));
		$gridGutter = intval(spLessCss::getLessParam('gridGutter'));
		//calcul des paramètres
		$elementWidth = $gridColWidth * $nbreCols + $gridGutter * ($nbreCols -1) - $padSub;

		return $elementWidth;
	}

	//calcul de la hauteur d'un élément en fonction du nombre de lignes passées en paramètre
	public static function gridGetHeight($nbreLine = 1, $padSub = 0) {
		//récupération des valeurs de dimension de la grille
		$nbreLine = intval($nbreLine);
		$padSub = intval($padSub);
		$gridBaseline = intval(spLessCss::getLessParam('gridBaseline'));
		//calcul des paramètres
		$elementHeight = ($gridBaseline * $nbreLine) - $padSub;

		return $elementHeight;
	}
	
	//calcul de la largeur du contenu
	public static function gridGetContentWidth(){
		//récupération du gabarit courant
		$currentGabarit = sfContext::getInstance()->getPage()->get('gabarit');
		if ($currentGabarit == 'default' || $currentGabarit == '') {
			$currentGabarit = spLessCss::getLessParam('templateGabarit');
		}

		//récupération des valeurs de colonnes
		$gridCol = spLessCss::lessCalculator(spLessCss::getLessParam('gridCol'));
		$gridCol_SidebarLeft = spLessCss::lessCalculator(spLessCss::getLessParam('gridCol_SidebarLeft'));
		$gridCol_SidebarRight = spLessCss::lessCalculator(spLessCss::getLessParam('gridCol_SidebarRight'));
		
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
		$contentWidth = spLessCss::gridGetWidth($gridCol_Content);
		
		return $contentWidth;
	}

	//permet de supprimer les paragraphes
	public static function textEditorStripParagraph($inputText) {
		$filteredText = str_replace('<p>', '', $inputText);
		$filteredText = str_replace('</p>', '', $filteredText);
		return $filteredText;
	}
}