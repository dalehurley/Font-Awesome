<?php  // var: $sites
if(count($sites) != NULL) {
    echo _tag('h4.title','Nos sites utiles');
    echo _open('ul.elements');
    foreach ($sites as $site) {
        echo _open('li.element');
        echo _link($site)->text(_tag('sapn.wrapper',$site->getTitle()))->set('.link_box');
        echo _close('li');
    }
    echo _close('ul');
}
