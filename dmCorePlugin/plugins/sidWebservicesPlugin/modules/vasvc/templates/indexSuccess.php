<form action="<?php echo url_for('vasvc/index') ?>" method="POST">
  <h1>Valeur acquise par une suite de versements constants</h1>
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
			<td align="left" class="texte1">Périodicité</td>
			<td align="right" class="texte1">Trimestre(s)</td>

		</tr>
		<tr>
			<td align="left" class="texte2">Fin de période ou Début de période </td>
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
	


	<?php
	} else {
		
  		$t=$sf_user->getFlash('results');
  		echo  html_entity_decode($t['soap']); 

	}
	?>


</form>