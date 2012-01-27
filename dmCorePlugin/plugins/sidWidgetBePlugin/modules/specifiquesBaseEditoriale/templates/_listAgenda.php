<?php
// var $agendas - var $rubriqueTitle - var $rubrique
$html = '';


if(count($agendas)){
	
	$html.= get_partial('global/titleWidget', array('title' => $rubriqueTitle));
	
	//ouverture du listing
    $html.= _open('ul.elements');
	
	//compteur
	$count = 0;
	$maxCount = count($agendas);
	
    foreach ($agendas as $agenda) {
		//incrémentation compteur
		$count++;
		
		$html.= get_partial('global/schema/Thing/CreativeWork/Article', array(
												'node' => $agenda,
												'count' => $count,
												'maxCount' => $maxCount,
												'container' => 'li.element',
												'isListing' => true,
												'dateCreated' => false,
												'descriptionLength' => $length,
												'url' => $agenda
												));
    }
	
    //fermeture du listing
    $html.= _close('ul.elements');
	
	//création d'un tableau de liens à afficher
	$elements = array();
	$elements[] = array('title' => $lien, 'linkUrl' => $agendas[0]->getSection());
	
	$html.= get_partial('global/navigationWrapper', array(
													'placement' => 'bottom',
													'elements' => $elements
													));
}

//affichage html en sortie
echo $html;