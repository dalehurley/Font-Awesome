<?php

echo _tag('h4.title','Calcul du capital emprunté');

echo '<h5>(Ce calcul suppose des versements constants)</h5>';

echo $form.'<hr style="clear:both; visibility:hidden;">';

if ($sf_user->hasFlash('results')) {

    $results = $sf_user->getFlash('results');

    //var_dump($results); // pour afficher les valeurs postée et le résultat soap

    echo $results['soap'];
    
} else { ?>

	<h2 >Exemple </h2>
	<em class="annotation">
		vous voulez connaître le capital que vous pouvez emprunter, sachant que vous pouvez rembourser 2 250 € par semestre sur 10 ans et que le taux proposé par l'organisme financier avoisinera 10 %.
	</em>
	
	<table>
		<tbody><tr>
			<td align="right">Montant des remboursements</td>

			<td align="left" >2 250</td>
		</tr>
		<tr>
			<td align="right" >Nombre de remboursements</td>
			<td align="left" >20</td>
		</tr>
		<tr>

			<td align="right" >Périodicité (Mois, Ans, Trimestres, Semestres)</td>
			<td align="left" >Semestres</td>
		</tr>
		<tr>
			<td align="right" >Versements Fin ou Début de période </td>
			<td align="left" >Versements Fin</td>
		</tr>

		<tr>
			<td align="right" >Taux proportionnel annuel</td>
			<td align="left" >10</td>
		</tr>
	</tbody></table>
	
	<h6>Résultat </h6>
	<em>

		le capital d'un emprunt remboursé sur 20 semestres au taux proportionnel annuel (frais inclus) de 10 % avec des versements constants de 2 250 € s'effectuant en fin de période est de <b>28 039.97 €</b>.
	</em>

<?php }