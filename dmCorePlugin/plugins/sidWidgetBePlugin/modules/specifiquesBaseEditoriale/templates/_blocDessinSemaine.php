<?php
// vars : $rubriqueTitle, $dessin, $titreLien
if (count($dessins)) { // si nous avons des actu articles
    echo _tag('h4.title', $rubriqueTitle);
    echo _open('ul.elements');
    echo _open('li.element');

    //on vérifie que l'image existe
    $imgExist = is_file(sfConfig::get('sf_web_dir') . $dessins['imgLinkBig']);
    $imageDessin = "";
    // on teste si le fichier image est présent sur le serveur avec son chemin absolu
    if ($imgExist) {
                  
        $html = "";
        $html.= _open('span.imageWrapper');
        $html.= _media($dessins['imgLinkSmall'])
                ->set('.image itemprop="image"')
                ->alt($dessins['titre'])
                ->width(spLessCss::gridGetWidth(sidSPLessCss::getLessParam('thumbL_col')));
        $html.= _close('span.imageWrapper');

        $html.= _open('span.wrapper');
        $html.= _tag('span.title itemprop="name"', $dessins['titre']);
        //on ajoute le chapeau dans tous les cas
        $html.= _tag('span.teaser itemprop="description"', $dessins['chapeau']);

        $html.= _close('span.wrapper');

        //On englobe l'ensemble du contenu dans un lien que l'on affiche
        echo _link('main/dessin')
                ->set('.link_box')
                ->title($dessins['titre'])
                ->text($html);

        // lien vers la page des dessins
        echo _open('div.navigation.navigationBottom');
        echo _link('main/dessin')->text($titreLien);
        echo _close('div');
    }


    echo _close('li');
    echo _close('ul');
}

