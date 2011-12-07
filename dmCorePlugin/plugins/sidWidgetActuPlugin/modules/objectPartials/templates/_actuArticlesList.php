<?php

// Vars: $article
    
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