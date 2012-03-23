<?php

echo _tag('h4.title','Déterminez rapidement les congés de votre salarié');

echo '<p><i>(Cas général : à préciser avec votre Convention Collective)</i></p>	
	<p>
		Il est possible, pour la période de référence allant du 1<sup>er</sup> juin au 31 mai, de traduire en chiffres les principes posés par le code du travail pour une durée hebdomadaire de travail répartie sur 5 jours (du lundi au vendredi).
		<br/>
		<br/>

		Afin de déterminer rapidement le nombre de jours ouvrables du congé de votre salarié, veuillez indiquer le nombre de jours d\'absence de celui-ci.
	</p>';

echo $form.'<hr style="clear:both; visibility:hidden;">';

if ($sf_user->hasFlash('results')) {

    $results = $sf_user->getFlash('results');

    //var_dump($results); // pour afficher les valeurs postée et le résultat soap

    echo $results['soap'];
    
}