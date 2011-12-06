<?php

// var $rubriqueTitle 
echo _tag('h4.title', $rubriqueTitle);

echo _open('ul.elements');

foreach ($dessins as $dessin) {
    echo _open('li.element');

    //on vérifie que l'image existe
    $imgExist = is_file(sfConfig::get('sf_web_dir') . $dessin['imgLinkBig']);
    $imageDessin = "";
    // on teste si le fichier image est présent sur le serveur avec son chemin absolu
    if ($imgExist) {
        $imageDessin = _open('div.imageFullWrapper');
        $imageDessin .= _media($dessin['imgLinkBig'])
                ->set('.image itemprop="image"')
                ->alt($dessin['titre'])
        //redimenssionnement propre lorsque l'image sera en bibliothèque
        //->width(spLessCss::gridGetContentWidth())
        ;
        //->height(spLessCss::gridGetHeight(14,0))
        $imageDessin .= _close('div');
    }

    echo $imageDessin;
    echo _tag('span.title',$dessin['titre']);
    echo _tag('span.teaser',$dessin['chapeau']);
    
}


echo _close('ul');
echo _close('li');

