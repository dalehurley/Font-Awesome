<form action="<?php echo url_for('ctr/index') ?>" method="POST">
  <h1>Calcul du taux réel (coût réel du crédit-bail)</h1>
  <table>
  
    <?php echo $form ?>
    <tr>
      <td colspan="2">
        <input type="submit" />
      </td>

    </tr>
  </table>

<?php if ($viewResultat)	{
      $t=$sf_user->getFlash('results');
      echo  html_entity_decode($t['soap']); 

};

	?>


</form>