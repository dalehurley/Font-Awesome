<?php
$debug = true;
include('nusoap-0.7.2/lib/nusoap.php');

$serveur = new soap_server;
$serveur->register('financier_placement'); 


/*------------------------------------------------------------------------------------------------------------
 *  financier-placement: Valeur acquise par un capital plac� � int�r�ts compos�s pendant une dur�e d�termin�e
 -------------------------------------------------------------------------------------------------------------
 */

function financier_placement($intCapital, $intPeriodicite, $intDuree, $blnDebut, $fltTaux) {

	$txM = 0.0;

	$txM = 1 + ($fltTaux / $intPeriodicite / 100);

	if($blnDebut == TRUE) $intDuree = $intDuree + 1;

	$result = $intCapital * (pow($txM, $intDuree));

	return $result;
}

$serveur->service($HTTP_RAW_POST_DATA);
?>