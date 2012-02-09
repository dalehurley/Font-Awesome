<?php
// vars : $section, $titreBloc, $titreLien, $longueurTexte, $articles, $arrayRubrique, $photo

$html = '';

if (count($articles)) { // si nous avons des actu articles
	
	$html.= get_partial('global/titleWidget', array('title' => $titreBloc));
	
	//ouverture du listing
    $html.= _open('ul.elements');
	
	//compteur
	$count = 0;
	$maxCount = count($articles);
	
    foreach ($articles as $article) {
		//incrémentation compteur
		$count++;
		
		//création d'un tableau de liens à afficher
		$elements = array();
		$elements[] = array('title' => $titreLien . '&#160;' . $arrayRubrique[$article->filename], 'linkUrl' => $article->Section);
		
		$html.= get_partial('global/schema/Thing/CreativeWork/Article', array(
												'name' => $article->getTitle(),
												'description' => $article->getChapeau(),
												'image' => '/_images/lea' . $article->filename . '-p.jpg',
												'dateCreated' => $article->created_at,
												'isDateMeta' => true,
												'count' => $count,
												'maxCount' => $maxCount,
												'container' => 'li.element',
												'isListing' => true,
												'descriptionLength' => $longueurTexte,
												'navigationBottomElements' => $elements,
												'url' => $article
												));
    }
	
    //fermeture du listing
    $html.= _close('ul.elements');
    
}

//affichage html en sortie
echo $html;