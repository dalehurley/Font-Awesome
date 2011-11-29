<?php
// vars $recrutements, $nbMissions, $titreBloc, $longueurTexte


if (count($recrutements)) { // si nous avons des actu articles
    
    echo _tag('h4.title', $titreBloc);
    
    echo _open('ul.elements');
    foreach ($recrutements as $recrutement) {

	include_partial("objectPartials/recrutementsList", array("recrutement" => $recrutement,"textLength" => $longueurTexte,"textEnd" => '(...)','titreBloc' => $titreBloc));

    }
    echo _close('ul');

    
} // sinon on affiche rien
