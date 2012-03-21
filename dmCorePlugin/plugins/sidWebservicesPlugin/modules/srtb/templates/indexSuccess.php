<form action="<?php echo url_for('srtb/index') ?>" method="POST">
  <h1>Seuil de rentabilité (estimation rapide)</h1>
  <table>
  <tr>
  	<td colspan="2">
  	<p>
  	Le seuil de rentabilité est le chiffre d'affaires (CA) pour lequel le résultat est égal à 0. C'est à dire le CA minimum qu'il vous faut réaliser pour ne pas perdre d'argent ou le CA à partir duquel votre projet devient bénéficiaire.
		<br/>
		<br/>Indice de sécurité : c'est le rapport entre votre chiffre d'affaires et le seuil de rentabilité.
		<br/>Exemple : 26 000/15 250 : l'indice de sécurité = 1,70.
		<br/>
		<br/>La marge de sécurité s'élève à 10 750 €, votre chiffre d'affaire peut baisser de 10 750 € avant que vous ne soyez en perte.
		<br/>
		<br/>Pour effectuer ce calcul, vous devez connaître le taux de marge brute dégagé par votre exploitation, le pourcentage de charges variables généré par l'exploitation, le volume de charges de structure (celles qui sont fixes, quel que soit le niveau du chiffre d'affaires) et le chiffre d'affaires annuel réalisé.
		<br/>
		<br/>Si vous êtes en entreprise individuelle, vous devez évaluer votre niveau de rémunération.
	</p>

	
	<h5 class="title">Saisissez vos données :</h5>

  	</td>
  </tr>
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
		




	<?php
	} else {
		

		  		$t=$sf_user->getFlash('results');
  		echo  html_entity_decode($t['soap']); 

	}
	?>


</form>