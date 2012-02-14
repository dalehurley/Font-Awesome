<?php
// Vars: $sitesUtilesPager

//récupération du titre
foreach ($sitesUtilesPager as $sitesUtiles) {
    $firstSitesUtilesGroup = $sitesUtiles->getGroupeSitesUtiles()->title;
    break;
}
$html = get_partial('global/titleWidget', array('title' => $firstSitesUtilesGroup));

//affichage du pager du haut
$html.= get_partial('global/navigationWrapper', array('placement' => 'top', 'pager' => $sitesUtilesPager));

//ouverture du listing
$html.= _open('ul.elements');

//compteur
$count = 0;
$maxCount = count($sitesUtilesPager);

foreach ($sitesUtilesPager as $sitesUtiles) {
	//incrémentation compteur
	$count++;
	
	//options de l'article
	$articleOpt = array(
					'name' => $sitesUtiles,
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
}
	
//fermeture du listing
$html.= _close('ul.elements');

//affichage du pager du bas
$html.= get_partial('global/navigationWrapper', array('placement' => 'bottom', 'pager' => $sitesUtilesPager));

//affichage html en sortie
echo $html;