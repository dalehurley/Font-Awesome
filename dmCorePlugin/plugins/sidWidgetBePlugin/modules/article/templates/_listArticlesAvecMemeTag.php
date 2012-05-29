<?php // Vars: $articles

$html = '';

if(count($articles) != NULL){
	
	$html.= get_partial('global/titleWidget', array('title' => __('Read also')));
	
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
												'dateCreated' => $article->created_at,
												'isDateMeta' => true,
												'count' => $count,
												'maxCount' => $maxCount,
												'container' => 'li.element',
												'isListing' => true,
												'url' => $article
												));
    }
	
    //fermeture du listing
    $html.= _close('ul.elements');
	
}
//affichage html en sortie
echo $html;