<?php
// vars : $equipes, $titreBloc, $titreLien, $pageEquipe, $lenght, $rubrique, $nomRubrique

$html = '';

if (count($equipes)) { // si nous avons des actu articles
	
	//affichage du titre du bloc
    if ($titreBloc != '') $html.= _tag('h4.title', $titreBloc);
	
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
    
	/*
    echo _open('div.navigation.navigationBottom');
	echo _open('ul.elements');
	    echo _open('li.element');
		echo _link('pageCabinet/equipe')->text($titreLien);
	    echo _close('li');
	echo _close('ul');
    echo _close('div');
	 */
} // sinon on affiche rien

//affichage html en sortie
echo $html;