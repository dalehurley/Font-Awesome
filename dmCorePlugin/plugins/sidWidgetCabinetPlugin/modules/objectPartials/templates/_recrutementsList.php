<?php

        $html = '';
        $html .= _open('span.wrapper');
        $html .= _tag('span.title',$recrutement);
        $html .= _tag('span.teaser',  stringTools::str_truncate($recrutement->getText(), $textLength, $textEnd, true, true));
        $html .= _close('span.wrapper');
        echo _open('li.element');
        echo _link($recrutement)->text($html)->set('.link_box');
        echo _close('li');
