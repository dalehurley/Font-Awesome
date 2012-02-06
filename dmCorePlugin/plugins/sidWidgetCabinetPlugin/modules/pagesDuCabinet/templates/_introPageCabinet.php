<?php
$html = '';

if (count($pageCabinet)) { // si nous avons des actu articles
	
	$html.= get_partial('global/titleWidget', array('title' => $titlePage));
	
	//ouverture du listing
    $html.= _open('ul.elements');
	
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', array(
												'name' => $pageCabinet->getTitle(),
												'description' => $pageCabinet->getResume(),
												'image' => $pageCabinet->getImage(),
												'dateCreated' => false,
												'count' => 1,
												'maxCount' => 1,
												'container' => 'li.element',
												'isListing' => true,
												'descriptionLength' => $lenght,
												'url' => $pageCabinet
												));
	//fermeture du listing
    $html.= _close('ul.elements');
	
	//création d'un tableau de liens à afficher
	$elements = array();
	$elements[] = array('title' => $lien, 'linkUrl' => $pageCabinet);
	
	$html.= get_partial('global/navigationWrapper', array(
													'placement' => 'bottom',
													'elements' => $elements
													));
	
} // sinon on affiche rien

//affichage html de sortie
echo $html;