<?php
if (count($missions)) { // si nous avons des actu articles
    echo _tag('h2.title', $titreBloc);
        include_partial("objectPartials/missionShow", array("mission" => $missions));
} // sinon on affiche rien
