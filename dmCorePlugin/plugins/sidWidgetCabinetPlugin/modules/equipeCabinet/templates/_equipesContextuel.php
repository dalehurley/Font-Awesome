<?php
// vars : $equipes, $titreBloc, $titreLien, $pageEquipe, $lenght, $rubrique, $nomRubrique
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
		
		$html.= get_partial('global/schema/Thing/Person', array(
														'node' => $equipe,
														'contactType' => $nomRubrique[$equipe->id],
														'container' => 'li.element',
														'count' => $count,
														'maxCount' => $maxCount,
														'isLight' => true
														));
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