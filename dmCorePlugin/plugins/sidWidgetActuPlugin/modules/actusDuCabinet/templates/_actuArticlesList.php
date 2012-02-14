<?php
$html = '';

if (count($articles)) { // si nous avons des actu articles
	
	//indique si le titre du bloc est le titre du contenu
	$isTitleContent = false;
	
	//gestion affichage du titre
    if($nbArticles == 1){
        if ($titreBloc != true) {
			$html.= get_partial('global/titleWidget', array('title' => current($articles)));
			$isTitleContent = true;
		}
		else $html.= get_partial('global/titleWidget', array('title' => $titreBloc));
    }
    else {
        if ($titreBloc != true) $html.= get_partial('global/titleWidget', array('title' => __('The other news of the office')));
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
						'description' => $article->getResume(),
						'dateCreated' => $article->created_at,
						'isDateMeta' => true,
						'count' => $count,
						'maxCount' => $maxCount,
						'container' => 'li.element',
						'isListing' => true,
						'descriptionLength' => $longueurTexte,
						'url' => $article
					);
		
		//on active le titre du contenu que lorsqu'il n'est pas affiché dans le titre du widget
		if(!$isTitleContent) $articleOpt['name'] = $article->getTitle();
		
		//on ajoute les photos pour les 3 premiers articles
		if($count <= 3) $articleOpt['image'] = $article->getImage();
		
		//ajout de l'article
		$html.= get_partial('global/schema/Thing/CreativeWork/Article', $articleOpt);
    }
	
	//fermeture du listing
    $html.= _close('ul.elements');
    
} else {
	// sinon on affiche la constante de la page concernée
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', array('container' => 'article', 'articleBody' => '{{actualites_du_cabinet}}'));
}

//affichage html en sortie
echo $html;