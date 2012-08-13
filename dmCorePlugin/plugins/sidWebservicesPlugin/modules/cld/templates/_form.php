<?php
if(dmConfig::get('site_theme_version') == 'v1'){
    echo _tag('h4.title','Calcul de la durée');

    echo $form;

    if ($sf_user->hasFlash('results')) {

        $results = $sf_user->getFlash('results');

        //var_dump($results); // pour afficher les valeurs postée et le résultat soap

        echo $results['soap'];

    }
}
elseif(dmConfig::get('site_theme_version') == 'v2'){
    echo _tag('h3','Calcul de la durée');

    echo $form->render(array('class' => 'form-horizontal'));

    if ($sf_user->hasFlash('results')) {

        $results = $sf_user->getFlash('results');

        //var_dump($results); // pour afficher les valeurs postée et le résultat soap

        echo $results['soap'];

    }
}