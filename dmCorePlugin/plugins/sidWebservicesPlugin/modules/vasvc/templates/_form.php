<?php

echo _tag('h4.title','Valeur acquise par une suite de versements constants');

echo $form.'<hr style="clear:both; visibility:hidden;">';

if ($sf_user->hasFlash('results')) {

    $results = $sf_user->getFlash('results');

    //var_dump($results); // pour afficher les valeurs postée et le résultat soap

    echo $results['soap'];
    
} else { ?>

<h6 class="title">Exemple </h6>

	<em class="annotation">
		vous pouvez épargner 700 € par trimestre que vous voulez placer au taux de 10 %. Quel sera votre capital dans 6 ans ?
	</em>
	
	<table>
		<tbody><tr>
			<td align="left" class="texte1">Montant des versements</td>
			<td align="right" class="texte1">700</td>
		</tr>

		<tr>
			<td align="left" class="texte2">Nombre de versements</td>
			<td align="right" class="texte2">24</td>
		</tr>
		<tr>
			<td align="left" class="texte1">Périodicité (M : mois, A : ans, T : trimestres, S : semestres)</td>
			<td align="right" class="texte1">T</td>

		</tr>
		<tr>
			<td align="left" class="texte2">Versements Fin ou Début de période (F ou D)</td>
			<td align="right" class="texte2">F</td>
		</tr>
		<tr>
			<td align="left" class="texte1">Taux proportionnel annuel</td>

			<td align="right" class="texte1">10</td>
		</tr>
	</tbody></table>
	
	<h6 class="title">Résultat </h6>
	<em class="annotation">
		le capital acquis au terme de 24 versements trimestriels de 700 € placés en fin de période au taux proportionnel annuel de 10 % est de <b>22 644,33 €</b>.
	</em>

<?php }