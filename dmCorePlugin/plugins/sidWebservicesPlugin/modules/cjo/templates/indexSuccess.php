<form action="<?php echo url_for('cjo/index') ?>" method="POST">
  <h1>Correspondance jours ouvrés/jours ouvrables</h1>
  <table>
    <tr>
    	<td colspan="2">
    		Calcul en jours ouvrables ou en jours ouvrés ?<br/>

En général, le nombre de jours de congés payés se comptabilise en jours ouvrables, soit tous les jours de la semaine à l'exception des dimanches et des jours fériés chômés.<br/>
Attention<br/>
votre convention collective peut prévoir un calcul en jours ouvrés.<br/>

Vous pouvez obtenir, ci dessous, l'équivalence de jours ouvrables en jours ouvrés et inversement. <br/>Cette table de concordance est calculée sur la base de 5 jours ouvrés pour 6 jours ouvrables.
Concordance<br/>
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
} ?>


</form>