<?php

echo _tag('h4','Valeur acquise par un capital placé à intérêts composés pendant une durée déterminée');

echo $form;

if ($sf_user->hasFlash('results')) {

    $results = $sf_user->getFlash('results');

    //var_dump($results); // pour afficher les valeurs postée et le résultat soap

    echo $results['soap'];
    
}