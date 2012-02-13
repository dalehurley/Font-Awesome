<?php
// vars : $missions, $nbMissions, $titreBloc, $longueurTexte
$html = '';

if (count($missions)) { // si nous avons des actu articles
	
	//indique si le titre du bloc est le titre du contenu
	$isTitleContent = false;
	
	//gestion affichage du titre
    if($nbMissions == 1){
        if ($titreBloc != true) {
			$html.= get_partial('global/titleWidget', array('title' => current($missions)));
			$isTitleContent = true;
		}
        else $html.= get_partial('global/titleWidget', array('title' => $titreBloc));
    }
    else {
        if ($titreBloc != true) $html.= get_partial('global/titleWidget', array('title' => __('The other missions of the office')));
        else $html.= get_partial('global/titleWidget', array('title' => $titreBloc));
    }
    
    //ouverture du listing
    $html.= _open('ul.elements');
	
	//compteur
	$count = 0;
	$maxCount = count($missions);
	
    foreach ($missions as $mission) {
		//incrémentation compteur
		$count++;
		
		//options de la mission
		$missionOpt = array(
							'description' => $mission->getResume(),
							'dateCreated' => $mission->created_at,
							'isDateMeta' => true,
							'count' => $count,
							'maxCount' => $maxCount,
							'container' => 'li.element',
							'isListing' => true,
							'descriptionLength' => $longueurTexte,
							'url' => $mission
							);
		
		//on active le titre du contenu que lorsqu'il n'est pas affiché dans le titre du widget
		if(!$isTitleContent) $missionOpt['name'] = $mission->getTitle();
		
		$html.= get_partial('global/schema/Thing/CreativeWork/Article', $missionOpt);
    }
	
    //fermeture du listing
    $html.= _close('ul.elements');
} // sinon on affiche rien

//affichage html de sortie
echo $html;