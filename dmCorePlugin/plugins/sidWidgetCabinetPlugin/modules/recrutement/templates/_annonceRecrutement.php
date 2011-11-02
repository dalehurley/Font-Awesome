<?php  // var: $annonces
if(count($annonces) != NULL) {
    echo _tag('h4.title',$dmPage);
    echo _open('ul.elements');
    foreach ($annonces as $annonce) {
        echo _open('li.element');
        echo _link($annonce);
        echo _close('li');
    }
    echo _close('ul');
}
