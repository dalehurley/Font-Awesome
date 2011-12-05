<?php
if (count($pageCabinet)) { // si nous avons des actu articles
    echo _tag('h2.title', $titlePage);
        include_partial("objectPartials/pageCabinet", array("pageCabinet" => $pageCabinet));
    echo _link('main/contact')->text($lien);
} // sinon on affiche rien
