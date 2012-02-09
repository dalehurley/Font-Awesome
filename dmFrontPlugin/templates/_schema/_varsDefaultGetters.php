<?php
/*
 * _varsDefaultGetters.php
 * v1.1
 * Permet d'extraire de façon standardisée des variables de node
 * 
 * Variables disponibles :
 * $node
 * $container
 * $descriptionLength
 * $navigationElements	indique les éléments de navigations
 * $count				indique le numéro de listing
 * $maxCount			indique le nombre maximal d'éléments affichages
 * 
 */

//récupérations des options de page
$pageOptions = spLessCss::pageTemplateGetOptions();
$isDev = $pageOptions['isDev'];
$isLess = $pageOptions['isLess'];

//récupération des valeurs par défaut
$includeDefaultValues = sfConfig::get('dm_front_dir') . '/templates/_schema/_varsDefaultValues.php';
include $includeDefaultValues;

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
	
	//application classe de debug
	if($isLess) $ctnOpts['class'][] = 'isVerified';
}

//Affectation des valeurs par défaut passées dans la node
if(isset($node)) {
	//Properties from Thing
	if(!isset($description)) {
		try { $description = strip_tags($node->getResume(), '<sup><sub>'); }
		catch(Exception $e) {
			//getTitleEntetePage
			//sinon on récupère le texte de la node (utile pour les Person)
			try { $description = strip_tags($node->getChapeau(), '<sup><sub>'); }
			catch(Exception $e) {
				try { $description = strip_tags($node->getText(), '<sup><sub>'); }
				catch(Exception $e) { $description = null; }
			}
		}
		//on raccourci la description si une longueur de description est définie
		/*if($description != null && isset($descriptionLength)) {
			$description = stringTools::str_truncate($description, $descriptionLength, '&#160;(...)', true, true);
		}*/
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
	
	//Properties from CreativeWork (rajouter autres variables quand implémentées) :
	if(!isset($dateCreated)) {
		try { $dateCreated = $node->getCreatedAt(); }
		catch(Exception $e) { $dateCreated = null; }
	}
	if(!isset($dateModified)) {
		try { $dateModified = $node->getUpdatedAt(); }
		catch(Exception $e) { $dateModified = null; }
	}
	
	//Properties from Article
	if(!isset($articleBody)) {
		try { $articleBody = $node->getText(); }
		catch(Exception $e) { $articleBody = null; }
	}
	if(!isset($articleSection)) {
		try {
			$section = $node->getSectionPageTitle();
			$rubrique = $node->getRubriquePagetitle();
			$articleSection = $rubrique . '&#160;-&#160;' . $section;
		}
		catch(Exception $e) { $articleSection = null; }
	}
        
        // rajout stef
        if(!isset($uploadFile)) {
		try { $uploadFile = $node->getFiles(); }
		catch(Exception $e) { $uploadFile = null; }
	}
        if(!isset($uploadFileTitle)) {
		try { 
                    $uploadFileTitle = ($node->getTitleFile() != '') ? $node->getTitleFile() : $node->getFiles()->file; }
		catch(Exception $e) { $uploadFileTitle = null; }
	}
        // fin rajout
}

//définition de l'image
$isImage = false;
if(isset($image)) if($image) {
	//on vérifie si l'image est en base (de type objet dans ce cas)
	$isDbImage = (gettype($image) == 'object') ? true : false;
	
	//si l'image est en base alors on rajoute le dossier upload devant pour obtenir l'url absolue
	$imageUpload = ($isDbImage) ? sfConfig::get('sf_upload_dir') . '/' : sfConfig::get('sf_web_dir') . '/';
	
	//composition url absolue de l'image
	$imageUrl = $imageUpload . $image;
	
	//test de la présence de l'image
	$isImage = is_file($imageUrl);
}