<?php
//Personnalisation de la mise en page par défaut
$pageOptionsCustom['areas']['dm_sidebar_right']['isActive'] = false;


//Récupération et chargement du template de page
$pSTInc = spLessCss::pageSuccessTemplateInclude();
if($pSTInc['isFile'])	include	$pSTInc['include'];
else					echo	$pSTInc['errorMsg'];