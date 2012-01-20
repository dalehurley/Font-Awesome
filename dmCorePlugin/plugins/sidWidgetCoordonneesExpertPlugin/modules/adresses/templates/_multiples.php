<?php
// vars : $adresses, $titreBloc
$html = '';

//$html.= get_partial('global/schema/DataType/Text', array(/*'type' => 'Address', */'value' => 'testValeur', 'itemprop' => 'url', 'url' => 'http://www.google.fr'/*, 'container' => 'div', 'customClass' => 'rubrique'*/));



if (count($adresses)) {
	//titre du contenu
	if($titreBloc) $html.= get_partial('global/titleWidget', array('title' => $titreBloc));
	
	//ouverture du listing
    //$html.= _open('ul.elements');
	
	//compteur
	$count = 0;
	$maxCount = count($adresses);
	
    foreach($adresses as $adresse) {
		//incrémentation compteur
		$count++;
		
		
		$html.= get_partial('global/schema/Thing/Intangible/StructuredValue/ContactPoint/PostalAddress', array('node' => $adresse, 'container' => 'div.test itemprop="address"'));
		$html.= '<br/>';
		$html.= '<br/>';
		/*
		
		$html.= get_partial('global/publicationListElement', array(
												'node' => $adresse,
												'itemType' => 'Organization',
												'count' => $count,
												'maxCount' => $maxCount
												));
		 * 
		 */
        //$html.= get_partial("objectPartials/adresseCabinet", array("renseignements" => $adresse));
    }
	
    //fermeture du listing
    //$html.= _close('ul.elements');
	
	
	
} // sinon on affiche rien


//affichage html de sortie
echo $html;