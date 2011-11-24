<?php

// Vars: $sidActuArticlePager
// mise en place de la prÃ©sentation de la liste des articles du cabinet
// initialisation du compteur
$i = 1;
echo _tag('h2.title', $pageName);

echo $sidActuArticlePager->renderNavigationTop();
echo _open('ul.elements');
 
foreach ($sidActuArticlePager as $sidActuArticle) {
    
    // premiÃ¨re boucle des 6 premiers articles avec photo, titre et chapeau (rÃ©sumÃ©)
    if ($i <= 6) {
        $html = '';
        
        if(($sidActuArticle->image) != NULL){
        $html = _open('span.imageWrapper');    
        $html .= _media($sidActuArticle->getImage())->height(120)->method('scale')->set('image');
        $html .= _close('span');
        };
        $html .= _open('span.wrapper');
        $html .= _tag('span.title',$sidActuArticle);
        $html .= _tag('span.title','Sujet : '.$sidActuArticle);
        $html .= _tag('span.teaser',$sidActuArticle->getResume());
        $html .= _close('span.wrapper');
        echo _open('li.element');
        echo _link($sidActuArticle)->text($html)->set('.link_box');
        echo _close('li');
        }
        else{
            echo _open('li.element');
           echo _link($sidActuArticle)->text(_tag('span.title',$sidActuArticle).' '._tag('span.teaser',$sidActuArticle->getResume()))->set('.link_box');
        }
        echo _close('li');
    
    // Ã  partir du 7Ã¨me, prÃ©sentation en lien sur le titre
    if ($i >= 7) {
        

        echo _open('li.element');

        echo _link($sidActuArticle)->text(ucfirst($sidActuArticle->getRubrique()).' > '.$sidActuArticle)->set('.link_box');

        echo _close('li');
    }

    
     
    $i++;
    
}
//
echo _close('ul');

echo $sidActuArticlePager->renderNavigationBottom();