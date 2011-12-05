<?php

if (count($missions)) { // si nous avons des actu articles
    if ($titreBloc != '') {
        echo _tag('h4.title', $titreBloc);
    } else {
        if ($nb > 1) {
            echo _tag('h4.title', __('Our missions'));
        } else {
            if ($nb == 1) {
                echo _tag('h4.title', current($missions)->getTitle());
            }
            else
                echo _tag('h4.title', __('Our missions'));
        }
    }

    echo _open('ul.elements');
    foreach ($missions as $mission) {
        echo _open('li.element');

        include_partial("objectPartials/mission", array("mission" => $mission, "length" => $length, "chapo" => $chapo, "nb" => $nb, 'titreBloc' => $titreBloc));

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
