<form action="<?php echo url_for('trsvc/index') ?>" method="POST">
  <h1>Taux de rendement d'une suite de versements constants</h1>
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

	<em >
		votre placement s'est fait par versements de 2 250 € sur un compte bloqué pendant 24 mois et votre capital s'élève aujourd'hui à 60 000 €, quel a été le rendement de votre placement ?
	</em>
	
	<table>
		<tbody><tr>
			<td align="right" >Montant des versements</td>
			<td align="left" >2 250</td>
		</tr>

		<tr>
			<td align="right">Nombre de versements</td>
			<td align="left" >24</td>
		</tr>
		<tr>
			<td align="right" >Périodicité</td>
			<td align="left" >M</td>

		</tr>
		<tr>
			<td align="right" >Fin de période ou Début de période </td>
			<td align="left" >Fin de période</td>
		</tr>
		<tr>
			<td align="right" >Capital acquis</td>

			<td align="left" >60 000</td>
		</tr>
	</tbody></table>
	
	<h6 class="title">Résultat </h6>
	<em class="annotation">
		le taux du placement annuel proportionnel est de <b>10.84 %</b>, soit un taux annuel équivalent de <b>11.4 %</b>.
	</em>
	

	<?php
	} else {
		

		  		$t=$sf_user->getFlash('results');
  		echo  html_entity_decode($t['soap']); 

	}
	?>


</form>