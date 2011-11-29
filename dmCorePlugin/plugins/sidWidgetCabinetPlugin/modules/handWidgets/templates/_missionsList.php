<?php

if (count($missions)) { // si nous avons des actu articles
    if($nbMissions == 1){
        if ($titreBloc != true) {
            echo _tag('h4.title', current($missions));
        }
        else  echo _tag('h4.title', $titreBloc);
    }
    else echo _tag('h4.title', $titreBloc);
    
    echo _open('ul.elements');
    foreach ($missions as $mission) {

	include_partial("objectPartials/missionsList", array("article" => $mission,"textLength" => $longueurTexte,"textEnd" => '(...)','titreBloc' => $titreBloc,"chapo" => $chapo));

    }
    echo _close('ul');

    
} // sinon on affiche rien
