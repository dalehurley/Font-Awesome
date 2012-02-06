<?php
//récupération des valeurs par défaut
$includeDefaultValues = sfConfig::get('dm_front_dir') . '/templates/_schema/_varsDefaultValues.php';
include $includeDefaultValues;

// vars : $adresse, $titreBloc
$html = '';

if (count($adresse)) {
	
	if($titreBloc != null) $html.= get_partial('global/titleWidget', array('title' => $titreBloc));
	
	//affichage du contenu
	$addressOpts = array(
						'container' => 'div.supWrapper',
						'name' => $adresse->getTitle(),
						'addressLocality' => $adresse->getVille(),
						'postalCode' => $adresse->getCodePostal(),
						'email' => $adresse->getEmail(),
						'faxNumber' => $adresse->getFax(),
						'telephone' => $adresse->getTel(),
						'container' => 'li.element',
						'count' => 1,
						'maxCount' => 1
					);
	$addressOpts['streetAddress'] = $adresse->getAdresse();
	if ($adresse->getAdresse2() != NULL) $addressOpts['streetAddress'].= $dash . $adresse->getAdresse2();
	
	$html.= get_partial('global/schema/Thing/Organization', $addressOpts);
} // sinon on affiche rien

//affichage html de sortie
echo $html;