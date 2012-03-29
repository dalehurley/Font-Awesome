<form action="<?php echo url_for('cce/index') ?>" method="POST">
  <h1>Calcul du capital emprunté</h1>
  <h5>(Ce calcul suppose des versements constants)</h5>
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
		<h6 class="title">Exemple </h6>
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
			<td align="right" >Fin de période ou Début de période </td>
			<td align="left" >Fin de période</td>
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

	<?php
	} else {
		

		  		$t=$sf_user->getFlash('results');
  		echo  html_entity_decode($t['soap']); 

	}
	?>


</form>