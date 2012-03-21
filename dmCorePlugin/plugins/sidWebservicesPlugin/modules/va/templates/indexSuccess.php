<form action="<?php echo url_for('va/index') ?>" method="POST">
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
		




	<?php
	} else {
		

		  		$t=$sf_user->getFlash('results');
  		echo  html_entity_decode($t['soap']); 

	}
	?>


</form>