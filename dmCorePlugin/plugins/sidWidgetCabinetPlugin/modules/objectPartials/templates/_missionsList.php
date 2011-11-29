<?php

        $html = '';
        $html .= _open('span.wrapper');
        $html .= _tag('span.title',$article);
        $html .= _tag('span.teaser',$article->getResume());
        $html .= _close('span.wrapper');
        echo _open('li.element');
        echo _link($article)->text($html)->set('.link_box');
        echo _close('li');
