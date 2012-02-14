<?php // Vars: $groupeSitesUtilesPager
$html = get_partial('global/titleWidget', array('title' => __('Different groups of useful sites')));

//affichage du pager du haut
$html.= get_partial('global/navigationWrapper', array('placement' => 'top', 'pager' => $groupeSitesUtilesPager));

//ouverture du listing
$html.= _open('ul.elements');

//compteur
$count = 0;
$maxCount = count($groupeSitesUtilesPager);

foreach ($groupeSitesUtilesPager as $groupeSitesUtiles) {
	//incrémentation compteur
	$count++;
	
	//options de l'article
	$articleOpt = array(
					'name' => $groupeSitesUtiles,
					'url' => $groupeSitesUtiles,
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
$html.= get_partial('global/navigationWrapper', array('placement' => 'bottom', 'pager' => $groupeSitesUtilesPager));

//affichage html en sortie
echo $html;