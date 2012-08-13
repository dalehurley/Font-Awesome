<?php
// vars : $titreBloc, $dessin, $lien
//on vérifie que l'image existe
$img = sfConfig::get('sf_web_dir') . $dessins['imgLinkBig'];
$imgExist = is_file($img);
$imageDessin = "";
if (dmConfig::get('site_theme_version') == 'v1'){
// on teste si le fichier image est présent sur le serveur avec son chemin absolu
if ($imgExist) {
    echo _tag('h4.title', $titreBloc);
    echo _open('ul.elements');
        echo _open('li.element.first.last');
            $html = "";
            $html.= _open('span.imageWrapper');
                $html.= _media($dessins['imgLinkSmall'])->set('.image itemprop="image"')->alt($dessins['titre'])
                //                ->width(spLessCss::gridGetWidth(sidSPLessCss::getLessParam('thumbL_col')))
                ;
            $html.= _close('span.imageWrapper');
            $html.= _open('span.wrapper');
                $html.= _tag('span.title itemprop="name"', $dessins['titre']);
                //on ajoute le chapeau dans tous les cas
                $html.= _tag('span.teaser itemprop="description"', $dessins['chapeau']);
            $html.= _close('span.wrapper');
            //On englobe l'ensemble du contenu dans un lien que l'on affiche
            echo _link('main/dessin')->set('.link_box')->title($dessins['titre'])->text($html);
            // lien vers la page des dessins
            if($lien != NULL){
            echo _open('span', array('class' => 'navigationWrapper navigationBottom'));
                echo _open('ul.elements');
                    echo _open('li.element.first.last');
                        echo _link('main/dessin')->text($lien);
                    echo _close('li');
                echo _close('ul');
            }
            echo _close('span');
        echo _close('li');
    echo _close('ul');
    
} else {
    echo debugTools::infoDebug(array(
        'fichier absent' => $img
    ) , 'warning');
}
}
elseif (dmConfig::get('site_theme_version') == 'v2'){
    if ($imgExist) {
        echo _tag('h3', $titreBloc);
        echo _open('ul');
            echo _open('li' , array('class' => 'thumbnails first last'));
                $html = "";
                $html.= _media($dessins['imgLinkSmall'])->alt($dessins['titre']);
                $html.= _open('div', array('class' => 'caption'));
                    $html.= _tag('h5', $dessins['titre']);
                    //on ajoute le chapeau dans tous les cas
                    $html.= _tag('p', $dessins['chapeau']);
                $html.= _close('div');
                //On englobe l'ensemble du contenu dans un lien que l'on affiche
                echo _link('main/dessin')->set('.thumbnail')->title($dessins['titre'])->text($html);
                // lien vers la page des dessins
                if($lien != NULL){
                echo _link('main/dessin')->text($lien)->set('.btn');
            echo _close('li');
        echo _close('ul');
        
    } else {
        echo debugTools::infoDebug(array(
            'fichier absent' => $img
        ) , 'warning');
    }
}
}
