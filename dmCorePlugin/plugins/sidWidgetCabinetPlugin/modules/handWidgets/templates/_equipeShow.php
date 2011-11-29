<?php

// vars  $equipes, $titreBloc


if (count($equipes)) { // si nous avons des actu articles
    echo _tag('h4.title', $titreBloc);
    $ville = "";

    foreach ($equipes as $equipe) {
        // si $ville est nulle, on ouvre la div, on place le nom de la ville et on ouvre le ul.elements
        if ($ville == "") {
            echo _open('div.blocImplentation');
            echo _tag('h4.title', __('Implentation') . $equipe->ImplentationId->ville);
            echo _open('ul.elements');

            $ville = $equipe->ImplentationId->ville;
        }

        // si $ville n'est pas nulle et identique à la ville d'implentation du membre, on execute uniquement le partial
        if ($ville == $equipe->ImplentationId->ville && $ville != "") {
            include_partial("objectPartials/equipeShow", array("equipe" => $equipe));
            $ville = $equipe->ImplentationId->ville;
        }

        // si $ville n'est pas nulle et différente de la ville d'implentation du membre, on ferme le ul, la div, 
        // ensuite on ouvre la div, on place le nom de la ville et on ouvre le ul.elements pour ensuite executer le partial
        if (($ville != $equipe->ImplentationId->ville) && $ville != "") {
            echo _close('ul.elements');
            echo _close('div.blocimplentation');
            echo _open('div.blocImplentation');
            echo _tag('h4.title', __('Implentation') . ' : ' . $equipe->ImplentationId->ville);
            echo _open('ul.elements');
            include_partial("objectPartials/equipeShow", array("equipe" => $equipe));

            $ville = $equipe->ImplentationId->ville;
        }
    }
    echo _close('ul');
} 
