<?php  // var $articles - var $rubriqueTitle - var $rubrique - var $photoArticle - var $titreBloc
// je gère l'affichage du site
if ((count($articles) == 1) && ($titreBloc == true)) {
    foreach ($articles as $article) {
        echo _tag('h4.title', $article->getTitle());
    }
} else {
    echo _tag('h4.title', $sectionName);
}

echo _open('ul.elements');
    
    include_partial("objectPartials/articleBlocHome", array("articles" => $articles, "photoArticle" => $photoArticle, "titreBloc" => $titreBloc ));
echo _close('ul');
echo _open('div.navigation.navigationBottom');
	echo _open('ul.elements');
		echo _open('li.element');
			echo _link($articles[0]->getSection())->text('Découvrir notre section '. $articles[0]->getSectionPageName().' de la rubrique '.$articles[0]->getRubriquePageName());
		echo _close('li');
	echo _close('ul');
echo _close('div');