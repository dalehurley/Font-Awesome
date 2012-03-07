<?php

echo '<h1>Valeur acquise par un capital placé à intérêts composés pendant une durée déterminée</h1>';

echo $form;

if ($sf_user->hasFlash('results')) {

    $results = $sf_user->getFlash('results');

    var_dump($results); // pour tester

    echo $results['soap'];
    
}