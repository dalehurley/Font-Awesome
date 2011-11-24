<?php
if (count($pageCabinet)) { // si nous avons des actu articles
    echo _tag('h4.title', $titlePage);
    echo _open('ul.elements');
        include_partial("objectPartials/introCabinet", array("pageCabinet" => $pageCabinet, "lenght" => $lenght));
    echo _close('ul.elements');
    echo _open('div.navigation.navigationBottom');
        echo _open('ul.elements');
            echo _open('li.element');
                echo _link($pageCabinet)->text(strtoupper($lien));
            echo _close('li');
        echo _close('ul');
    echo _close('div');
} // sinon on affiche rien
