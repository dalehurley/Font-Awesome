<?php
if (count($missions)) { // si nous avons des actu articles
    if ($titreBloc != '') {
	echo _tag('h4.title', $titreBloc);
    }
    else echo _tag('h4.title', 'Nos Missions');

    echo _open('ul.elements');
    foreach ($missions as $mission) {
	echo _open('li.element');

	//$articleTitle =  $article->getRubriquePageName() . ' :: ' . $article->getSectionPageName();
	//echo _link($article)->text($article)->title($article->getRubriquePageName() . ' :: ' . $article->getSectionPageName());

	include_partial("objectPartials/mission", array("mission" => $mission, "textLength" => 200, "textEnd" => " ..."));

	echo _close('li');
    }
    echo _close('ul');
    
    echo _open('div.navigation.navigationBottom');
	echo _open('ul.elements');
	    echo _open('li.element');
		echo _link('mission/list')->text($titreLien);
	    echo _close('li');
	echo _close('ul');
    echo _close('div');    
} // sinon on affiche rien
