<?php

echo _tag('h4.title','Valeur acquise par un capital placé à intérêts composés pendant une durée déterminée');

echo $form;

if ($sf_user->hasFlash('results')) {

    $results = $sf_user->getFlash('results');

    //var_dump($results); // pour afficher les valeurs postée et le résultat soap

    echo $results['soap'];
    
} else { ?>

	<h2>Exemple </h2>

	<em class="annotation">
		vous voulez savoir quel sera le montant de votre capital, actuellement de 34 000 €  si vous le placez sur un compte bloqué pendant 5 ans au taux de 9 %.
	</em>
	
	<table>
		<tbody><tr>
			<td >Montant du capital placé</td>
			<td >34 000</td>
		</tr>

		<tr>
			<td >Durée du placement (en nombre de périodes)</td>
			<td >5</td>
		</tr>
		<tr>
			<td >Périodicité</td>
			<td >Ans</td>

		</tr>
		<tr>
			<td >Versements Fin ou Début de période</td>
			<td >Versements Fin</td>
		</tr>
		<tr>
			<td >Taux proportionnel annuel</td>

			<td >9</td>
		</tr>
	</tbody></table>
	
	<h2>Résultat</h2>
	<em>
		un capital de 34 000 € placé en fin de période pendant 5 ans au taux proportionnel annuel de 9 % s'élève à <b>52 313,21 €</b>.
	</em>

<?php }