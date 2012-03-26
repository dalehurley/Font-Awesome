<form action="<?php echo url_for('acs/index') ?>" method="POST">
  <h1>Absences et congés du salarié</h1>
  <table>
   <tr>
   	<td colspan="2">
   		<h5 class="title">Déterminez rapidement les congés de votre salarié</h5>
	<p><i>(Cas général : à préciser avec votre Convention Collective)</i></p>	
	<p>
		Il est possible, pour la période de référence allant du 1<sup>er</sup> juin au 31 mai, de traduire en chiffres les principes posés par le code du travail pour une durée hebdomadaire de travail répartie sur 5 jours (du lundi au vendredi).
		<br/>
		<br/>

		Afin de déterminer rapidement le nombre de jours ouvrables du congé de votre salarié, veuillez indiquer le nombre de jours d'absence de celui-ci.
	</p>

   	</td>

   </tr>
    <?php echo $form ?>
    <tr>
      <td colspan="2">
        <input type="submit" />
      </td>

    </tr>
  </table>

<?php 
if ($viewResultat) {
  $t=$sf_user->getFlash('results');
  echo  html_entity_decode($t['soap']); 
}
  ?>

</form>