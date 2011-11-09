<?php

if (count($articles)) { // si nous avons des actu articles
    if ($titreBloc != '') {
	echo _tag('h4.title', $titreBloc);
    }

    echo _open('ul.elements');
    foreach ($articles as $article) {

	include_partial("objectPartials/actuArticle", array("article" => $article));

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
