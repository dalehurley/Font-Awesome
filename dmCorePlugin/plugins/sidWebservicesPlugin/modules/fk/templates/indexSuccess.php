<form action="<?php echo url_for('fk/index') ?>" method="POST">
  <h1>Calcul des frais kilométriques : véhicules automobiles</h1>
  <table>
  
    <?php echo $form ?>
    <tr>
      <td colspan="2">
        <input type="submit" />
      </td>

    </tr>
  </table>

<?php if ($viewResultat) echo  html_entity_decode($resulat);


	?>


</form>