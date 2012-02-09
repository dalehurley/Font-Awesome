<?php
//insertions des includes nécessaires à ce partial
$initValues = sfConfig::get('dm_front_dir') . '/templates/_schema/_partialInitValues.php';
include $initValues;

// vars : $adresses, $titreBloc
$html = '';

if (count($adresses)) {
	//titre du contenu
	if($titreBloc) $html.= get_partial('global/titleWidget', array('title' => $titreBloc));
	
	//ouverture du listing
    $html.= _open('ul.elements');
	
	//compteur
	$count = 0;
	$maxCount = count($adresses);
	
    foreach($adresses as $adresse) {
		//incrémentation compteur
		$count++;
		
		//affichage du contenu
		$addressOpts = array(
							'name' => $adresse->getTitle(),
							'addressLocality' => $adresse->getVille(),
							'postalCode' => $adresse->getCodePostal(),
							'email' => $adresse->getEmail(),
							'faxNumber' => $adresse->getFax(),
							'telephone' => $adresse->getTel(),
							'container' => 'li.element',
							'count' => $count,
							'maxCount' => $maxCount
						);
		$addressOpts['streetAddress'] = $adresse->getAdresse();
		if ($adresse->getAdresse2() != NULL) $addressOpts['streetAddress'].= $dash . $adresse->getAdresse2();
		
		$html.= get_partial('global/schema/Thing/Organization', $addressOpts);
    }
	
    //fermeture du listing
    $html.= _close('ul.elements');
} // sinon on affiche rien

//affichage html de sortie
echo $html;