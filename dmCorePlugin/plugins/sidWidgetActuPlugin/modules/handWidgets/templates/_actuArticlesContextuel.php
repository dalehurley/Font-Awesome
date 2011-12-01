<?php
// vars : $articles, $titreBloc, $longueurTexte, $nbArticles, $photo, $titreLien, $chapo

if (count($articles)) { // si nous avons des actu articles
    if($nbArticles == 1){
        if ($titreBloc != true) {
            echo _tag('h4.title', current($articles));
        }
        else  echo _tag('h4.title', $titreBloc);
    }
    else echo _tag('h4.title', $titreBloc);
    echo _open('ul.elements');
    foreach ($articles as $article) {

	include_partial("objectPartials/actuArticle", array("article" => $article,"textLength" => $longueurTexte,"textEnd" => '(...)',"photo" => $photo,"nbArticle" => $nbArticles,'titreBloc' => $titreBloc,"chapo" => $chapo));

    }
    echo _close('ul');

    echo _open('div.navigation.navigationBottom');
	echo _open('ul.elements');
	    echo _open('li.element');
		echo _link('sidActuArticle/list')->text($titreLien);
	    echo _close('li');
	echo _close('ul');
    echo _close('div');
} // sinon on affiche rien
