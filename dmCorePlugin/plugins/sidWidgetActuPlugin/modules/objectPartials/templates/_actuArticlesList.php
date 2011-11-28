<?php

// Vars: $sidActuArticlePager
// mise en place de la prÃ©sentation de la liste des articles du cabinet
// initialisation du compteur
$i = 1;
    
    // premiÃ¨re boucle des 2 premiers articles avec photo, titre et chapeau (rÃ©sumÃ©)
//    if ($i <= 2) {
        $html = '';
        
        if(($article->image) != NULL){
        $html = _open('span.imageWrapper');    
        $html .= _media($article->getImage())->height(120)->method('scale')->set('image');
        $html .= _close('span');
        };
        $html .= _open('span.wrapper');
        $html .= _tag('span.title',$article);
        $html .= _tag('span.teaser',$article->getResume());
        $html .= _close('span.wrapper');
        echo _open('li.element');
        echo _link($article)->text($html)->set('.link_box');
        echo _close('li');
//        }
//        else{
//            echo _open('li.element');
//           echo _link($article)->text(_tag('span.title',$article).' '._tag('span.teaser',$article->getResume()))->set('.link_box');
//        }
//        echo _close('li');
    
    // Ã  partir du 3Ã¨me, prÃ©sentation en lien sur le titre
//    if ($i >= 6) {
//        
//
//        echo _open('li.element');
//
//        echo _link($article)->text(ucfirst($article->getRubrique()).' > '.$article)->set('.link_box');
//
//        echo _close('li');
//    }

    
     
//    $i++;