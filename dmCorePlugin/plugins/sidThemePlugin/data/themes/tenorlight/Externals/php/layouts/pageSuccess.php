<?php
//Récupération et chargement du template de page
$pSTInc = spLessCss::pageSuccessTemplateInclude();
if($pSTInc['isFile'])	include	$pSTInc['include'];
else					echo	$pSTInc['errorMsg'];