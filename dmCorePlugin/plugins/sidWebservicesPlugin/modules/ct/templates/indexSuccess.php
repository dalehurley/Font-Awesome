<form action="<?php echo url_for('ct/index') ?>" method="POST">
  <h1>Calcul du taux</h1>
  <table>
  
    <?php echo $form ?>
    <tr>
      <td colspan="2">
        <input type="submit" />
      </td>

    </tr>
  </table>

<?php if (!$viewResultat)
	{ 
	?>
		
	
	<h6 >Exemple </h6>
	<em >
		votre banquier vous propose un tableau d'emprunt et vous voulez vérifier que le taux qu'il vous annonce est bien le taux réel de votre emprunt. Vous empruntez 76 225 €, le montant de chacun des 20 versements trimestriels s'élève à 5 030 €. Quel est le taux de votre emprunt ?
	</em>
	
	<table>
		<tbody><tr>
			<td align="right" >Capital emprunté</td>

			<td align="left" >76 225</td>
		</tr>
		<tr>
			<td align="right" >Montant des remboursements</td>
			<td align="left" >5 030</td>
		</tr>
		<tr>

			<td align="right" >Nombre des remboursements</td>
			<td align="left" >20</td>
		</tr>
		<tr>
			<td align="right" >Périodicité </td>
			<td align="left" >Trimestres</td>
		</tr>

		<tr>
			<td align="right" >Versements Fin ou Début de période </td>
			<td align="left" >Versements Fin</td>
		</tr>
	</tbody></table>
	
	<h6 >Résultat </h6>
	<em >

		un capital emprunté de 76 225 € remboursé sur 20 trimestres, avec des versements constants de 5 030 €, s'effectuant en fin de période est soumis au taux effectif suivant : taux annuel proportionnel (frais inclus) : <b>11.21 %</b>, soit un taux annuel équivalent (frais inclus) de <b>11.69 %</b>.
	</em>
		




	<?php
	} else {
		

		  		$t=$sf_user->getFlash('results');
  		echo  html_entity_decode($t['soap']); 

	}
	?>


</form>