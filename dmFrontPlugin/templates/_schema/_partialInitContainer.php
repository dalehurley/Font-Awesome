<?php
/*
 * _partialInitContainer.php
 * v1.0
 * Permet d'initialiser le container du partial
 * 
 * Variables disponibles :
 * $container
 * $noMicrodata			désactive tout ajout de microdata (désactivé par défaut)
 * $count				indique le numéro de listing
 * $maxCount			indique le nombre maximal d'éléments affichages
 * 
 */

//désactive tout ajout de microdata
if(!isset($noMicrodata)) $noMicrodata = false;

//déclaration des propriétés par défaut du container
$ctnOpts = array();
if(isset($container)) {
	//on ne rajoute ces éléments de microdata que si nécessaire
	if(!$noMicrodata) {
		$ctnOpts['class'][] = 'itemscope';
		$ctnOpts['class'][] = $itemType;
		$ctnOpts['itemscope'] = 'itemscope';
		$ctnOpts['itemtype'] = 'http://schema.org/' . $itemType;
	}
	
	//gestion de l'index de positionnement
	if(isset($count) && isset($maxCount)) {
		if($count == 1)			$ctnOpts['class'][] = 'first';
		if($count >= $maxCount)	$ctnOpts['class'][] = 'last';
	}
}