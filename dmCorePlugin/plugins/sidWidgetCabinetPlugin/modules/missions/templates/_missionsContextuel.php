<?php
$html = '';

if (count($missions)) { // si nous avons des actu articles
	
	//indique si le titre du bloc est le titre du contenu
	$isTitleContent = false;
	
	//gestion affichage du titre
    if ($titreBloc != '') {
		$html.= get_partial('global/titleWidget', array('title' => $titreBloc));
    } else {
        if ($nb > 1) {
			$html.= get_partial('global/titleWidget', array('title' => __('Our missions')));
        } else {
            if ($nb == 1) {
				$html.= get_partial('global/titleWidget', array('title' => current($missions)->getTitle()));
				$isTitleContent = true;
			}
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
							'description' => $mission->getResume(),
							'dateCreated' => $mission->created_at,
							'isDateMeta' => true,
							'count' => $count,
							'maxCount' => $maxCount,
							'container' => 'li.element',
							'isListing' => true,
							'descriptionLength' => $length,
							'url' => $mission
							);
		//on active le titre du contenu que lorsqu'il n'est pas affiché dans le titre du widget
		if(!$isTitleContent) $missionOpt['name'] = $mission->getTitle();
		
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