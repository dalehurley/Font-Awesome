<?php
// vars $recrutements, $nbMissions, $titreBloc, $longueurTexte
$html = get_partial('global/titleWidget', array('title' => $titreBloc));

if (count($recrutements)) { // si nous avons des actu articles
    
    //ouverture du listing
    $html.= _open('ul.elements');
	
	//compteur
	$count = 0;
	$maxCount = count($recrutements);
	
    foreach ($recrutements as $recrutement) {
		//incrémentation compteur
		$count++;
		
		$html.= get_partial('global/publicationListElement', array(
												'node' => $recrutement,
												'count' => $count,
												'maxCount' => $maxCount,
												'teaser' => $recrutement->getText(),
												'teaserLength' => $longueurTexte,
												'linkUrl' => $recrutement
												));
    }
	
    //fermeture du listing
    $html.= _close('ul.elements');
}else{
	$html.= get_partial('global/publicationShow', array('content' => '{{recrutement}}'));
}

//affichage html en sortie
echo $html;