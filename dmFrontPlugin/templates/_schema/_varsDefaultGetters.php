<?php
/*
 * _varsDefaultGetters.php
 * v1.0
 * Permet d'extraire de façon standardisée des variables de node
 * 
 * Variables disponibles :
 * $node
 * $container
 * 
 */

//déclaration des propriétés par défaut du container
$ctnOpts = array();
if(isset($container)) {
	$ctnOpts['class'][] = $itemType;
	$ctnOpts['itemscope'] = 'itemscope';
	$ctnOpts['itemtype'] = 'http://schema.org/' . $itemType;
}

//Affectation des valeurs par défaut passées dans la node
if(isset($node)) {
	//Properties from Thing
	if(!isset($description)) {
		try { $description = $node->getResume(); }
		catch(Exception $e) { $description = null; }
	}
	if(!isset($image)) {
		try { $image = $node->getImage(); }
		catch(Exception $e) { $image = null; }
	}
	if(!isset($name)) {
		try { $name = $node->getTitle(); }
		catch(Exception $e) { $name = null; }
	}
	if(!isset($url)) $url = null;		//on affecte pas l'url à une valeur car elle impliquerait la génération d'un lien imposée sinon
	
	//Properties from ContactPoint :
	/*if(!isset($contactType)) {
		try { $contactType = $nomRubrique[$node->id]; }		//cf partie équipe
		catch(Exception $e) { $contactType = null; }
	}*/
	if(!isset($email)) {
		try { $email = $node->getEmail(); }
		catch(Exception $e) { $email = null; }
	}
	if(!isset($faxNumber)) {
		try { $faxNumber = $node->getFax(); }
		catch(Exception $e) { $faxNumber = null; }
	}
	if(!isset($telephone)) {
		try { $telephone = $node->getTel(); }
		catch(Exception $e) { $telephone = null; }
	}
	
	//Properties from PostalAddress (rajouter autres variables quand implémentées :
	if(!isset($addressLocality)) {
		try { $addressLocality = $node->getVille(); }
		catch(Exception $e) { $addressLocality = null; }
	}
	if(!isset($streetAddress)) {
		try {
			$streetAddress = $node->getAdresse();
			if ($node->getAdresse2() != NULL) $streetAddress.= '&#160;-&#160;' . $node->getAdresse2();
		}
		catch(Exception $e) { $streetAddress = null; }
	}
}