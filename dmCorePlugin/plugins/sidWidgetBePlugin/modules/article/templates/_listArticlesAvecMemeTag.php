<?php // Vars: $articles

if(count($articles) != NULL){
    echo _tag('h4.title', __('Related articles'));
	echo _open('ul.elements');
		foreach ($articles as $articleTag) {
			include_partial("objectPartials/listArticlesAvecMemeTag", array("articleTag" => $articleTag));
		}
	echo _close('ul');
}