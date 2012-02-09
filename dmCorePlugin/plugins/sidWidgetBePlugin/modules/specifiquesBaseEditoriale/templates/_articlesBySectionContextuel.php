<?php
// vars : $articles, $titreBloc, $titreLien, $longueurTexte, $articles, $rubrique, $section
if (count($articles)) { // si nous avons des actu articles

    echo _tag('h4.title', $titreBloc);
    echo _open('ul.elements');
    foreach ($articles as $article){

	include_partial("objectPartials/articleBySectionContextuel", array("article" => $article,"textLength" => $longueurTexte,"textEnd" => '(...)'));

    }
    echo _close('ul');
    
    echo _open('div.navigation.navigationBottom');
    echo _open ('ul.elements');
        echo _open ('li.element');
            if($titreLien){$text = $titreLien;}
            else $text = $section->title.' '.__('in').' '. $rubrique->name;
            echo _link($article->Section)->text($text);
        echo _close('li');
    echo _close('ul');
    echo _close('div');
}