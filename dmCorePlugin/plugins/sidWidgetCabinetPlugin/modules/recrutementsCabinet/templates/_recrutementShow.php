<?php
// vars : $recrutements, $titreBloc
if (count($recrutements)) { // si nous avons des actu articles
    echo _tag('h2.title', $titreBloc);
        include_partial("objectPartials/recrutementShow", array("recrutement" => $recrutements));
} // sinon on affiche rien
