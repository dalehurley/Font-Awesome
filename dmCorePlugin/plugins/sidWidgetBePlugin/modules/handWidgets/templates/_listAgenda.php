<?php
// var $agendas - var $rubriqueTitle - var $rubrique
echo _tag('h4.title', $rubriqueTitle);

echo _open('ul.elements');
foreach ($agendas as $agenda) {

    include_partial("objectPartials/listAgenda", array("agenda" => $agenda, "textLength" => $length, "textEnd" => '(...)'));
}
echo _close('ul');

if (count($agendas)) {
    echo _open('div.navigation.navigationBottom');
    echo _open('ul.elements');
    echo _open('li.element');
    echo _link($agendas[0]->getSection())->text($lien);
    echo _close('li');
    echo _close('ul');
    echo _close('div');
}