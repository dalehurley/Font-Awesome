<?php

$imgLink = '/uploads/' . $pageCabinet[0]->getImage();
    $html = '';
//on vérifie que l'image existe
    $imgExist = is_file(sfConfig::get('sf_web_dir') . $imgLink);
    if ($imgExist) {
        $html.= _open('span.imageWrapper');
        $html.= _media($imgLink)
                ->set('.image itemprop="image"')
                ->alt($pageCabinet[0]->getTitle())
                ->width(myUser::gridGetWidth(myUser::getLessParam('thumbL_col')))
                ->height(myUser::gridGetHeight(myUser::getLessParam('thumbL_bl')));
        $html.= _close('span.imageWrapper');
    }
    if (strlen($pageCabinet[0]->getText()) > $lenght) {
        $chapeauEntier = substr($pageCabinet[0]->getText(), 0, $lenght);
        $space = strrpos($chapeauEntier, ' ');
        $chapo = substr($chapeauEntier, 0, $space) . ' (...)';
    }
    else
        $chapo = $pageCabinet[0]->getText();
//                        echo _tag('div.wrapper', $chapo);


    $html.= _open('span.wrapper');
    //on ajoute le chapeau dans tous les cas

    $html.= _tag('span.teaser itemprop="description"', $chapo);
    $html.= _close('span.wrapper');

    //On englobe l'ensemble du contenu dans un lien que l'on affiche
    echo _open('li.element');
    echo $html;
    echo _close('li.element');
?>
