<?php

$imgLink = '/uploads/' . $pageCabinet->getImage();
    $html = '';
//on vérifie que l'image existe
    $imgExist = is_file(sfConfig::get('sf_web_dir') . $imgLink);
    if ($imgExist) {
        $html.= _open('span.imageWrapper');
        $html.= _media($imgLink)
                ->set('.image itemprop="image"')
                ->alt($pageCabinet->getTitle())
                ->width(myUser::gridGetWidth(myUser::getLessParam('thumbL_col')))
                ->height(myUser::gridGetHeight(myUser::getLessParam('thumbL_bl')));
        $html.= _close('span.imageWrapper');
    }
// on vérifie d'abord si le texte est plus long que la longueur demandé
// si oui, on tronque après un espace
    if (strlen($pageCabinet->getText()) > $lenght) {
        $chapo = stringTools::str_truncate($pageCabinet->getText(), $lenght, '(...)', true);
    }
    else
// si non, on laisse le texte original
        $chapo = $pageCabinet->getText();

    $html.= _open('span.wrapper');
    //on ajoute le chapeau dans tous les cas

    $html.= _tag('span.teaser itemprop="description"', $chapo);
    $html.= _close('span.wrapper');

    //On englobe l'ensemble du contenu dans un lien que l'on affiche
    echo _open('li.element');
    echo $html;
    echo _close('li.element');
?>
