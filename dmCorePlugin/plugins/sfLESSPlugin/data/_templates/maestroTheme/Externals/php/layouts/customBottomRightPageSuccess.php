<?php
//Personnalisation de la mise en page par défaut
$pageOptionsCustom['areas']['dm_custom_bottom_left']['isPage'] = false;
$pageOptionsCustom['areas']['dm_custom_bottom_right']['isPage'] = true;

//Récupération et chargement du template de page
$pSTInc = spLessCss::pageSuccessTemplateInclude();
if($pSTInc['isFile'])	include	$pSTInc['include'];
else					echo	$pSTInc['errorMsg'];