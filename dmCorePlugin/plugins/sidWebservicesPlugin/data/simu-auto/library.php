<?php
require_once('validation.php');
require_once('utils.php');

/*---------------------------------------------------------------------------------------------------
 *  simu-auto : Simulateur auto-entrepreneur
 ----------------------------------------------------------------------------------------------------
 */
 
function simuauto($blnActivite, $intPerioderecette, $intMontantrecettes) {

	if($blnActivite == ventes) $intTaux = '13';
	if($blnActivite == ventes) $intTaux = '23';
	if($blnActivite == ventes) $intTaux = '20.5';
	
	$result = $intMontantrecettes * $intTaux;

	return $result;
}

/*---------------------------------------------------------------------------------------------------
 *  vacpicddService : Methode d'interface publique du protocole soap pour le service de simu-auto
 ----------------------------------------------------------------------------------------------------
 */
 
function simuautoService($activite, $perioderecette, $montantrecettes)  {
	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('Montant', $montantrecettes, '10000');
	
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a &eacute;t&eacute; d&eacute;tect&eacute;e.', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.
	$result = simuauto($activite, $perioderecette, $montantrecettes);

	//Formattage de la phrase de retour du service.
	
	$resultFormat = format_decimal($result);
	
	$resultPhrase = $t."<span class='text'>Le total des charges $perioderecette à régler est égal à <strong>$resultFormat &#8364;</strong>. </span>";
	
	//Retour positif.
	return array($resultPhrase, $errorMessages);
}