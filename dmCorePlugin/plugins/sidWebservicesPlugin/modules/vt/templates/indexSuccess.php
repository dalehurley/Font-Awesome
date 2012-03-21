<form action="<?php echo url_for('vt/index') ?>" method="POST">
  <h1>Versement transport</h1>
  <table>
  <tr>Les employeurs occupant plus de 9 salariés dans certaines communes ou groupements de communes (districts, communautés urbaines, communautés de villes ou de communes, etc...) de plus de 10 000 habitants sont redevables d'un versement destiné aux transports en commun, assis sur les rémunérations soumises à cotisations de sécurité sociale.
Pour connaître le taux de versement transport applicable dans votre ville :
  <td colspan="2">
  <p>Les employeurs occupant plus de 9 salariés dans certaines communes ou groupements de communes (districts, communautés urbaines, communautés de villes ou de communes, etc...) de plus de 10 000 habitants sont redevables d'un versement destiné aux transports en commun, assis sur les rémunérations soumises à cotisations de sécurité sociale.</p>
	<h5 >Pour connaître le taux de versement transport applicable dans votre ville :</h5>

  </td>
  </tr>
    <?php echo $form ?>
    <tr>
      <td colspan="2">
        <input type="submit" />
      </td>

    </tr>
  </table>

<?php if ($viewResultat) {
  $t=$sf_user->getFlash('results');
  echo  html_entity_decode($t['soap']); 
}
	?>


</form>