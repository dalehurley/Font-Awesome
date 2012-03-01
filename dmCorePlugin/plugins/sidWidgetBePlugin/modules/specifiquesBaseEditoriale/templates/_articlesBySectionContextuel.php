<?php
// vars : $articles, $titreBloc, $lien, $length, $rubrique, $section

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
		
		$html.= get_partial('global/schema/Thing/CreativeWork/Article', array(
												'name' => $article->getTitle(),
												'description' => $article->getChapeau(),
												'dateCreated' => $article->created_at,
												'isDateMeta' => true,
												'count' => $count,
												'maxCount' => $maxCount,
												'container' => 'li.element',
												'isListing' => true,
												'descriptionLength' => $length,
												'url' => $article
												));
    }
	
    //fermeture du listing
    $html.= _close('ul.elements');
	
	//création d'un tableau de liens à afficher
	$elements = array();
	$elements[] = array('title' => ($lien ? $lien : $section->title . ' en ' . $rubrique->name), 'linkUrl' => 'sidActuArticle/list');
	
	$html.= get_partial('global/navigationWrapper', array(
													'placement' => 'bottom',
													'elements' => $elements
													));
}

//affichage html en sortie
echo $html;