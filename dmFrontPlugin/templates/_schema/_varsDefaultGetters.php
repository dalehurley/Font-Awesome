<?php
/*
 * _varsDefaultGetters.php
 * v1.0
 * Permet d'extraire de façon standardisée des variables de node
 * 
 * Variables disponibles :
 * $node
 * $container
 * $separator
 * $isLight
 * 
 */

//déclaration des propriétés par défaut du container
$ctnOpts = array();
if(isset($container)) {
	$ctnOpts['class'][] = $itemType;
	$ctnOpts['itemscope'] = 'itemscope';
	$ctnOpts['itemtype'] = 'http://schema.org/' . $itemType;
}

//séparateur par défaut
if(!isset($separator)) $separator = '&#160;:&#160;';

//permet d'indiquer une version light de l'affichage (pour les listings simples)
if(!isset($isLight)) $isLight = false;

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
	
	//Properties from PostalAddress (rajouter autres variables quand implémentées) :
	if(!isset($addressLocality)) {
		try { $addressLocality = $node->getVille(); }
		catch(Exception $e) { $addressLocality = null; }
	}
	if(!isset($postalCode)) {
		try { $postalCode = $node->getCodePostal(); }
		catch(Exception $e) { $postalCode = null; }
	}
	if(!isset($streetAddress)) {
		try {
			$streetAddress = $node->getAdresse();
			if ($node->getAdresse2() != NULL) $streetAddress.= $separator . $node->getAdresse2();
		}
		catch(Exception $e) { $streetAddress = null; }
	}
	
	//Properties from Person (rajouter autres variables quand implémentées) :
	if(!isset($jobTitle)) {
		try { $jobTitle = $node->getStatut(); }
		catch(Exception $e) { $jobTitle = null; }
	}
}

//définition de l'image
$isImage = false;
if(isset($image)) {
	//on vérifie que l'image existe sur le serveur avec son chemin absolu
	$imageUpload = (strpos($image, 'uploads') === false) ? '/uploads/' : '/';
	$isImage = is_file(sfConfig::get('sf_web_dir') . $imageUpload . $image);
}