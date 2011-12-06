<?php

$html = "";
$html .= _open('li.element');
$html.= _open('span.wrapper');
$html.= _tag('span.title itemprop="name"', $article->getTitle());
//on ajoute le chapeau dans tous les cas
$html.= _tag('span.teaser itemprop="description"', stringTools::str_truncate($article->getChapeau(), $textLength, $textEnd, true));

$html.= _close('span.wrapper');

//On englobe l'ensemble du contenu dans un lien que l'on affiche
echo _link($article)
        ->set('.link_box')
        ->title($article->getTitle())
        ->text($html);
$html .= _close('li');
?>
