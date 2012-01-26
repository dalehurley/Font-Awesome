<?php
/*
 * _varsDefaultGetters.php
 * v1.0
 * Permet d'extraire de façon standardisée des variables de node
 * 
 * Variables disponibles :
 * $node
 * $container
 * $noMicrodata			désactive tout ajout de microdata (désactivé par défaut)
 * $separator
 * $isListing			indique qu'il s'agit d'un listing
 * $isLight				permet d'indiquer une version allégée (notamment pour des affichages spéciaux dans les sidebars)
 * $descriptionLength
 * $navigationElements	indique les éléments de navigations
 * $count				indique le numéro de listing
 * $maxCount			indique le nombre maximal d'éléments affichages
 * 
 */

//récupérations des options de page
$pageOptions = spLessCss::pageTemplateGetOptions();
$isDev = $pageOptions['isDev'];

//séparateur par défaut
if(!isset($separator)) $separator = '&#160;:&#160;';
//permet de forcer la version listing de l'affichage
if(!isset($isListing)) $isListing = false;
//permet d'indiquer une version light de l'affichage (pour les listings simples)
if(!isset($isLight)) $isLight = false;
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
	
	//application classe de debug
	if($isDev) $ctnOpts['class'][] = 'isVerified';
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
		try { $dateCreated = $node->created_at; }
		catch(Exception $e) { $dateCreated = null; }
	}
	if(!isset($dateModified)) {
		try { $dateModified = $node->updated_at; }
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
}

//définition de l'image
$isImage = false;
if(isset($image)) if($image) {
	//on vérifie que l'image existe sur le serveur avec son chemin absolu
	$imageUpload = (strpos($image, 'uploads') === false) ? '/uploads/' : '/';
	$isImage = is_file(sfConfig::get('sf_web_dir') . $imageUpload . $image);
}