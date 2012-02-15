<?php
$html = '';

if (count($pageCabinet)) { // si nous avons des actu articles
	
	$html.= get_partial('global/titleWidget', array('title' => $titlePage));
	
	//ouverture du listing
    $html.= _open('ul.elements');
	
	//options de l'article
	$articleOpt = array(
					'description' => $pageCabinet->getResume(),
					'image' => $pageCabinet->getImage(),
					'dateCreated' => $pageCabinet->created_at,
					'isDateMeta' => true,
					'count' => 1,
					'maxCount' => 1,
					'container' => 'li.element',
					'isListing' => true,
					'descriptionLength' => $lenght,
					'url' => $pageCabinet
					);
	
	//on active le titre du contenu que lorsqu'il n'est pas affiché dans le titre du widget
	if(!$isTitleContent) $articleOpt['name'] = $pageCabinet->getTitle();
	
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', $articleOpt);
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