<?php

// Vars: $sidActuRubriquePager - $actuRubriques

if (count($sidActuRubriquePager) != NULL) {
    echo _tag('h4.title', ucfirst($pageName));
    echo $sidActuRubriquePager->renderNavigationTop();
    echo _open('ul.elements');
    foreach ($sidActuRubriquePager as $sidActuRubrique) {
        include_partial("objectPartials/actuRubriqueList", array("sidActuRubrique" => $sidActuRubrique, "actuRubriques" => $actuRubriques));
        };
    echo _close('ul');
    echo $sidActuRubriquePager->renderNavigationBottom();
}
