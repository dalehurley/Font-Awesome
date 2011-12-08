<?php
// vars : $adresses, $titreBloc
if (count($adresses)) {
    if($titreBloc){// si nous avons des actu articles
    echo _tag('h4.title', $titreBloc);
    }
    foreach($adresses as $adresse){
        include_partial("objectPartials/adresseCabinet", array("renseignements" => $adresse));
    }
} // sinon on affiche rien
