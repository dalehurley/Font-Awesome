<?php

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

	include_partial("objectPartials/actuArticlesList", array("article" => $article,"textLength" => $longueurTexte,"textEnd" => '(...)',"photo" => $vars['photo'],'titreBloc' => $titreBloc,"chapo" => $chapo));

    }
    echo _close('ul');

    
} // sinon on affiche rien
