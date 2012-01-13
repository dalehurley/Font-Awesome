<?php

    echo _open('li.element');
    echo _link($tag)->text(_tag('span.wrapper',$tag->getTitle()))->set('.link_box'); 
    echo _close('li');

?>
