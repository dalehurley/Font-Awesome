<?php

if (count($articles)) { // si nous avons des actu articles
    if($nbArticles == 1){
        if ($titreBloc != true) {
            echo _tag('h4.title', current($articles));
        }
        else  echo _tag('h4.title', $titreBloc);
    }
    else echo _tag('h4.title', $titreBloc);
    // $nb mis à zero pour afficher les photos que sur les 3 premiers articles
    $nb = 0;
    echo _open('ul.elements');
    foreach ($articles as $article) {

	include_partial("objectPartials/actuArticlesList", array("article" => $article,"textLength" => $longueurTexte,"textEnd" => '(...)',"photo" => $vars['photo'],'titreBloc' => $titreBloc,"chapo" => $chapo,"nb" => $nb));
        // incrément de $nb pour afficher les photos que sur les 3 premiers articles
        $nb++;
    }
    echo _close('ul');

    
} // sinon on affiche la constante de la page concernée
else echo'{{actualites_du_cabinet}}';