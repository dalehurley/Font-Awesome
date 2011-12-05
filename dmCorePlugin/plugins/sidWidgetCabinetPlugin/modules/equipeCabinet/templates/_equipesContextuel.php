<?php
if (count($equipes)) { // si nous avons des actu articles
    if ($titreBloc != '') {
	echo _tag('h4.title', $titreBloc);
    }

    echo _open('ul.elements');
    foreach ($equipes as $equipe) {
	include_partial("objectPartials/introEquipe", array("equipe" => $equipe));
    }
    echo _close('ul');
    
    echo _open('div.navigation.navigationBottom');
	echo _open('ul.elements');
	    echo _open('li.element');
		echo _link('pageCabinet/equipe')->text($titreLien);
	    echo _close('li');
	echo _close('ul');
    echo _close('div');    
} // sinon on affiche rien
