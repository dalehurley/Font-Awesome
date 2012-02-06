<?php
// vars : $articles, $titreBloc, $longueurTexte, $nbArticles, $photo, $titreLien, $chapo
$html = '';

if (count($articles)) { // si nous avons des actu articles
	
	//gestion affichage du titre
    if($nbArticles == 1){
        if ($titreBloc != true) $html.= get_partial('global/titleWidget', array('title' => current($articles)));
        else $html.= get_partial('global/titleWidget', array('title' => $titreBloc));
    }
    else {
        if ($titreBloc != true) $html.= get_partial('global/titleWidget', array('title' => __('The news of the office')));
        else $html.= get_partial('global/titleWidget', array('title' => $titreBloc));
    }
	
	//ouverture du listing
    $html.= _open('ul.elements');
	
	//compteur
	$count = 0;
	$maxCount = count($articles);
	
	foreach ($articles as $article) {
		//incrémentation compteur
		$count++;
		
		//options de l'article
		$articleOpt = array(
						'node' => $article,
						'count' => $count,
						'maxCount' => $maxCount,
						'container' => 'li.element',
						'isListing' => true,
						'descriptionLength' => $longueurTexte,
						'url' => $article
					);
		
		//on supprime les photos après les 3 premiers articles
		if($count > 3) $articleOpt['image'] = false;
		
		//ajout de l'article
		$html.= get_partial('global/schema/Thing/CreativeWork/Article', $articleOpt);
    }
	
	//fermeture du listing
    $html.= _close('ul.elements');
	
	//création d'un tableau de liens à afficher
	$elements = array();
	$elements[] = array('title' => $titreLien, 'linkUrl' => 'sidActuArticle/list');
	
	$html.= get_partial('global/navigationWrapper', array(
													'placement' => 'bottom',
													'elements' => $elements
													));
} // sinon on affiche rien

//affichage html en sortie
echo $html;