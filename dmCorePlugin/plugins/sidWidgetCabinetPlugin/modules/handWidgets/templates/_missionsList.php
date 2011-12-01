<?php
// vars : $missions, $nbMissions, $titreBloc, $longueurTexte
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

	include_partial("objectPartials/missionsList", array("article" => $mission,"longueurTexte" => $longueurTexte,"textEnd" => '(...)','titreBloc' => $titreBloc));

    }
    echo _close('ul');

    
} // sinon on affiche rien
