<?php
$html = '';

if (count($missions)) { // si nous avons des actu articles
	
	//gestion affichage du titre
    if ($titreBloc != '') {
		$html.= get_partial('global/titleWidget', array('title' => $titreBloc));
    } else {
        if ($nb > 1) {
			$html.= get_partial('global/titleWidget', array('title' => __('Our missions')));
        } else {
            if ($nb == 1) $html.= get_partial('global/titleWidget', array('title' => current($missions)->getTitle()));
            else $html.= get_partial('global/titleWidget', array('title' => __('Our missions')));
        }
    }
	
	//ouverture du listing
    $html.= _open('ul.elements');
	
	//compteur
	$count = 0;
	$maxCount = count($missions);
	
    foreach ($missions as $mission) {
		//incrémentation compteur
		$count++;
		
		//option de la mission
		$missionOpt = array(
							'node' => $mission,
							'count' => $count,
							'maxCount' => $maxCount,
							'container' => 'li.element',
							'isListing' => true,
							'descriptionLength' => $length,
							'url' => $mission
							);
		//désactivation du titre si définit dans le titre du bloc
		if($titreBloc == null) $missionOpt['name'] = false;
		
		$html.= get_partial('global/schema/Thing/CreativeWork/Article', $missionOpt);
    }
	
	//fermeture du listing
    $html.= _close('ul.elements');
	
	//création d'un tableau de liens à afficher
	$elements = array();
	$elements[] = array('title' => $titreLien, 'linkUrl' => 'mission/list');
	
	$html.= get_partial('global/navigationWrapper', array(
													'placement' => 'bottom',
													'elements' => $elements
													));
} // sinon on affiche rien

//echo __('This bias needs to be a news item');

//affichage html de sortie
echo $html;