<?php
// vars : $adresse, $titreBloc
if (count($adresse)) {
    if($titreBloc){// si nous avons des actu articles
    echo _tag('h4.title', $titreBloc);
    }
    
        include_partial("objectPartials/adresseCabinet", array("renseignements" => $adresse));
} // sinon on affiche rien
