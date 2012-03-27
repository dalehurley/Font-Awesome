<?php

echo _tag('h4.title','Versement transport');

echo '  <p>Les employeurs occupant plus de 9 salariés dans certaines communes ou groupements de communes (districts, communautés urbaines, communautés de villes ou de communes, etc...) de plus de 10 000 habitants sont redevables d\'un versement destiné aux transports en commun, assis sur les rémunérations soumises à cotisations de sécurité sociale.</p>
	<h5 >Pour connaître le taux de versement transport applicable dans votre ville :</h5>';

echo $form;

if ($sf_user->hasFlash('results')) {

    $results = $sf_user->getFlash('results');

    //var_dump($results); // pour afficher les valeurs postée et le résultat soap

    echo $results['soap'];
    
}