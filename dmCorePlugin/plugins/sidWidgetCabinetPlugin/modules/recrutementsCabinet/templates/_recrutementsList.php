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
		
		$html.= get_partial('global/schema/Thing/CreativeWork/Article', array(
												'name' => $recrutement->getTitle(),
												'description' => $recrutement->getText(),
												'count' => $count,
												'maxCount' => $maxCount,
												'container' => 'li.element',
												'isListing' => true,
												'descriptionLength' => $longueurTexte,
												'url' => $recrutement
												));
    }
	
    //fermeture du listing
    $html.= _close('ul.elements');
}else{
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', array('container' => 'article', 'articleBody' => '{{recrutement}}'));
}

//affichage html en sortie
echo $html;