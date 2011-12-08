<?php

echo _open('li.element');
echo _open('span.wrapper');
//composition du html contenu dans le lien (ne peux contenir que des span)

$html = _tag('span.title', $agenda->getTitle());
//on ajoute le chapeau dans tous les cas

$html.= _tag('span.teaser', stringTools::str_truncate($agenda->getChapeau(), $textLength, $textEnd, true));
//On englobe l'ensemble du contenu dans un lien que l'on affiche
echo _link($agenda)
        ->set('.link_box')
        ->title($agenda->getTitle())
        ->text($html);
echo _close('span');
echo _close('li');