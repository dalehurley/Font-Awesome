<?php
// vars : $missions, $nbMissions, $titreBloc, $longueurTexte
$html = '';

if (count($missions)) { // si nous avons des actu articles
	
	//gestion affichage du titre
    if($nbMissions == 1){
        if ($titreBloc != true) $html.= get_partial('global/titleWidget', array('title' => current($missions)));
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
		
		$html.= get_partial('global/schema/Thing/CreativeWork/Article', array(
												'name' => $mission->getTitle(),
												'description' => $mission->getResume(),
												'count' => $count,
												'maxCount' => $maxCount,
												'container' => 'li.element',
												'isListing' => true,
												'descriptionLength' => $longueurTexte,
												'url' => $mission
												));
    }
	
    //fermeture du listing
    $html.= _close('ul.elements');
} // sinon on affiche rien

//affichage html de sortie
echo $html;