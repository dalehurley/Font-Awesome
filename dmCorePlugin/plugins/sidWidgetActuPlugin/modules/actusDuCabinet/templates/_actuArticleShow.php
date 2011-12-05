<?php
// vars : $articles, $titreBloc

if (count($articles)) { // si nous avons des actu articles

        if ($titreBloc != true) {
            echo _tag('h4.title', $articles->getTitle());
        }
        else {
            echo _tag('h4.title', $titreBloc);
        }
    
//    foreach ($articles as $article) {

	include_partial("objectPartials/actuArticleShow", array("articles" => $articles,'titreBloc' => $titreBloc));

//    }
    
} // sinon on affiche rien
