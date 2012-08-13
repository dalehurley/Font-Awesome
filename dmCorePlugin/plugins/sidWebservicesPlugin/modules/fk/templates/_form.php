<?php
if(dmConfig::get('site_theme_version') == 'v1'){
    echo _tag('h4.title','Calcul des frais kilométriques : véhicules automobiles');

    echo $form;

    if ($sf_user->hasFlash('results')) {

        $results = $sf_user->getFlash('results');

        //var_dump($results); // pour afficher les valeurs postée et le résultat soap

        echo $results['soap'];

    }
}
elseif(dmConfig::get('site_theme_version') == 'v2'){
    echo _tag('h3','Calcul des frais kilométriques : véhicules automobiles');

    echo $form->render(array('class' => 'form-horizontal'));

    if ($sf_user->hasFlash('results')) {

        $results = $sf_user->getFlash('results');

        //var_dump($results); // pour afficher les valeurs postée et le résultat soap

        echo $results['soap'];

    }
}