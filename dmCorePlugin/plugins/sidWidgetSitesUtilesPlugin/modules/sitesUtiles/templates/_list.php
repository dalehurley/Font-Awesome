<?php // Vars: $sitesUtilesPager
//affichage du pager du haut
$html = get_partial('global/navigationWrapper', array('placement' => 'top', 'pager' => $sitesUtilesPager));

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
					'url' => $sitesUtiles,
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