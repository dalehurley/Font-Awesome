<?php

// var $pageCabinet - $lenght
if (count($pageCabinet)) { // si nous avons des actu articles
    echo _tag('h4.title', $pageCabinet[0]->getTitleEntetePage());
    echo _open('ul.elements');
    //lien vers l'image
    include_partial("objectPartials/introCabinet", array("pageCabinet" => $pageCabinet, "lenght" => $lenght));
    echo _close('ul.elements');

    echo _open('div.navigation.navigationBottom');
    echo _open('ul.elements');
    echo _open('li.element');
    echo _link($pageCabinet[0])->text('en savoir plus sur {{nomcabinet}}');
    echo _close('li');
    echo _close('ul');
    echo _close('div');
} // sinon on affiche rien
