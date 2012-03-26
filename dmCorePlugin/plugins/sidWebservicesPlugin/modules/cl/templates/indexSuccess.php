<form action="<?php echo url_for('cl/index') ?>" method="POST">
  <h1>Calcul des loyers (constants)</h1>
  <table>
  
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