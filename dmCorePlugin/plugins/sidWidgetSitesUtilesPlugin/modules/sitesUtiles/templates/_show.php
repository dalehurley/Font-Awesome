<?php // Vars: 
$html = get_partial('global/titleWidget', array('title' => $sitesUtiles));

//ouverture du listing
$html.= _open('ul.elements');

//compteur
$count = 1;
$maxCount = 1;

//options de l'article
$articleOpt = array(
				'description' => $sitesUtiles->description,
				'image' => $sitesUtiles->getImage(),
				'url' => $sitesUtiles->url,
				'isUrlBlank' => true,
				'count' => $count,
				'maxCount' => $maxCount,
				'container' => 'li.element',
				'isListing' => true
			);

//ajout de l'article
$html.= get_partial('global/schema/Thing/CreativeWork/Article', $articleOpt);
	
//fermeture du listing
$html.= _close('ul.elements');

//affichage html en sortie
echo $html;