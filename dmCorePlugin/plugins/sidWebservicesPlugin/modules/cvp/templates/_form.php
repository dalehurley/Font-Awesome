<?php

echo _tag('h4.title','Calcul du versement périodique');

echo $form;

if ($sf_user->hasFlash('results')) {

    $results = $sf_user->getFlash('results');

    //var_dump($results); // pour afficher les valeurs postée et le résultat soap

    echo $results['soap'];
    
} else { ?>

		<h6 class="title">Exemple</h6>
	<em>
		au 1<sup>er</sup> janvier 2002, votre emprunt s'élève à 76 225 €, vous espérez pouvoir avoir fini de le rembourser dans 5 ans par périodes trimestrielles, sachant que votre banquier vous prête le capital au taux de 11.5 %. Quel sera le montant du versement trimestriel ?
	</em>
	
	<table>
		<tbody><tr>

			<td align="right">Capital emprunté</td>
			<td align="left">76 225</td>
		</tr>
		<tr>
			<td align="right">Nombre des remboursements</td>
			<td align="left">20</td>
		</tr>

		<tr>
			<td align="right">Périodicité </td>
			<td align="left">Trimestres</td>
		</tr>
		<tr>
			<td align="right">Versements Fin ou Début de période </td>
			<td align="left">Versements Fin </td>

		</tr>
		<tr>
			<td align="right">Taux proportionnel annuel</td>
			<td align="left">11,5</td>
		</tr>
	</tbody></table>
	
	<h6 >Résultat </h6>

	<em>
		le montant des versements d'un emprunt de 76 225 € dont les remboursements s'effectuent en fin de période sur une durée de 20 trimestres et soumis au taux proportionnel annuel de 11.5 % est de <b>5 064.49 €</b>.
	</em>

<?php } 