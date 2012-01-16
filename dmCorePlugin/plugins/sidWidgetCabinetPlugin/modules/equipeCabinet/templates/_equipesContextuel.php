<?php
// vars : $equipes, $titreBloc, $titreLien, $pageEquipe, $lenght, $rubrique, $nomRubrique
$html = '';

if (count($equipes)) { // si nous avons des actu articles
	
	//affichage du titre du bloc
    if($titreBloc != null) $html.= _tag('h4.title', $titreBloc);
	
	//ouverture du listing
    $html.= _open('ul.elements');
	
	//compteur
	$count = 0;
	$maxCount = count($equipes);
	
    foreach ($equipes as $equipe) {
		//incrémentation compteur
		$count++;
		
		$html.= get_partial('global/publicationListElement', array(
												'node' => $equipe,
												'itemType' => 'Person',
												'title' => $equipe->getTitle(),
												'image' => $equipe->getImage(),
												'rubrique' => $nomRubrique[$equipe->id],
												'count' => $count,
												'maxCount' => $maxCount,
												'isLight' => true
												));
    }
	
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