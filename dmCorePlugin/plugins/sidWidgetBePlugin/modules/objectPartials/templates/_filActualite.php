<?php

echo _open('li.element');
if($photo == 1){
//lien vers l'image
$imgLink = '/_images/lea' . $article->filename . '-p.jpg';
//on vérifie que l'image existe
$imgExist = is_file(sfConfig::get('sf_web_dir') . $imgLink);
}
else $imgExist = false;
//composition du html contenu dans le lien (ne peux contenir que des span)
$html = '';

if ($imgExist) {
    $html.= _open('span.imageWrapper');
    $html.= _media($imgLink)
            ->set('.image itemprop="image"')
            ->alt($article->getTitle())
            ->width(spLessCss::gridGetWidth(spLessCss::getLessParam('thumbL_col')))
            ->height(spLessCss::gridGetHeight(spLessCss::getLessParam('thumbL_bl')));
    $html.= _close('span.imageWrapper');

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
} else {
    echo _link($article)->text(
                    _tag('span.wrapper', _tag('span.title', '' . $article) .
                            _tag('span.teaser itemprop="description"', stringTools::str_truncate($article->getChapeau(), $textLength, $textEnd, true))
                    )
            )
            ->set('.link_box');
}
echo _open('div.navigation.navigationBottom');
//	echo _open('ul.elements');
//	    echo _open('li.element');
echo _link($article->Section)->text($titreLien . ' ' . $arrayRubrique[$article->filename]);
//	    echo _close('li');
//	echo _close('ul');
echo _close('div');
echo _close('li');
?>
