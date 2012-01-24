<?php
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
		
		$html.= get_partial('global/schema/Thing/Organization', array(
														'node' => $adresse,
														'container' => 'li.element',
														'count' => $count,
														'maxCount' => $maxCount
														));
    }
	
    //fermeture du listing
    $html.= _close('ul.elements');
} // sinon on affiche rien

//affichage html de sortie
echo $html;