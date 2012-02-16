<?php
// vars : $equipes, $titreBloc, $titreLien, $pageEquipe, $length, $rubrique, $nomRubrique
$html = '';

if (count($equipes)) { // si nous avons des actu articles
	
	//affichage du titre du bloc
    if($titreBloc != null) $html.= get_partial('global/titleWidget', array('title' => $titreBloc));
	
	//ouverture du listing
    $html.= _open('ul.elements');
	
	//compteur
	$count = 0;
	$maxCount = count($equipes);
	
    foreach ($equipes as $equipe) {
		//incrémentation compteur
		$count++;
		
		//options des personnes
		$personOpt = array(
						'name' => $equipe->getTitle(),
						'description' => $equipe->getText(),
						'image' => $equipe->getImage(),
						'email' => $equipe->getEmail(),
						'faxNumber' => $equipe->getFax(),
						'telephone' => $equipe->getTel(),
						'jobTitle' => $equipe->getStatut(),
						'container' => 'li.element',
						'count' => $count,
						'maxCount' => $maxCount,
						'isLight' => true
						);
		//rajout de la responsabilité seulement si présent
		if(array_key_exists($equipe->id, $nomRubrique)) $personOpt['contactType'] = $nomRubrique[$equipe->id];
		
		$html.= get_partial('global/schema/Thing/Person', $personOpt);
    }
	
	//fermeture du listing
    $html.= _close('ul.elements');
	
	//création d'un tableau de liens à afficher
	$elements = array();
	$elements[] = array('title' => $titreLien, 'linkUrl' => 'pageCabinet/equipe');
	
	$html.= get_partial('global/navigationWrapper', array(
													'placement' => 'bottom',
													'elements' => $elements
													));
} // sinon on affiche rien

//affichage html en sortie
echo $html;