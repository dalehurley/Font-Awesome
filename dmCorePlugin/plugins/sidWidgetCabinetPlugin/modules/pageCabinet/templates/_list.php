<?php // Vars: $pageCabinetPager

//echo $pageCabinetPager->renderNavigationTop();
//
//echo _open('ul.elements');
//
//foreach ($pageCabinetPager as $pageCabinet)
//{
//  echo _open('li.element');
//
//    echo _link($pageCabinet);
//
//  echo _close('li');
//}
//
//echo _close('ul');
//
//echo $pageCabinetPager->renderNavigationBottom();

echo _open('ul.elements');

foreach ($pageCabinetList as $pageCabinet) {
    echo _open('li.element');
        echo _open('span.teaser');
            echo _link($pageCabinet)->text(ucfirst(strtolower($pageCabinet->getTitle())))->set('.link_box');
        echo _close('span');
    echo _close('li');
}
    echo _open('li.element');
        echo _open('span.teaser');
            echo _link('pageCabinet/equipe')->set('.link_box');
        echo _close('span');
    echo _close('li');
    echo _open('li.element');
        echo _open('span.teaser');
            echo _link('recrutement/list')->set('.link_box');
        echo _close('span');
    echo _close('li');
    echo _open('li.element');
        echo _open('span.teaser');
            echo _link('sidActuRubrique/list')->set('.link_box');
        echo _close('span');
    echo _close('li');
    echo _open('li.element');
        echo _open('span.teaser');
            echo _link('pageCabinet/planDAcces')->set('.link_box');
        echo _close('span');
    echo _close('li');


echo _close('ul');
