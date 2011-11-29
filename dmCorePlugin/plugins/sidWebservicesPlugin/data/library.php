<?php
require_once('validation.php');
require_once('utils.php');

/*---------------------------------------------------------------------------------------------------
 *  vacpicdd : Valeur acquise par un capital placé à intérêts composés pendant une durée déterminée 
 ----------------------------------------------------------------------------------------------------
 */
 
function vacpicdd($intCapital, $intPeriodicite, $intDuree, $blnDebut, $fltTaux) {

	$txM = 0.0;
	
	$txM = 1 + ($fltTaux / $intPeriodicite / 100);

	if($blnDebut == TRUE) $intDuree = $intDuree + 1;
		
	$result = $intCapital * (pow($txM, $intDuree));

	return $result;
}

/*---------------------------------------------------------------------------------------------------
 *  vacpicddService : Methode d'interface publique du protocole soap pour le service de vacpicdd
 ----------------------------------------------------------------------------------------------------
 */
 
function vacpicddService($capital, $periodicite, $duree, $debut, $taux)  {
	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('Capital', $capital, '10000').
			checkInteger('Dur&eacute;e', $duree, '24').
			checkDecimal('Taux', $taux, '3.2');
	
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a &eacute;t&eacute; d&eacute;tect&eacute;e.', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.
	$result = vacpicdd($capital, $periodicite, $duree, $debut, $taux);

	//Formattage de la phrase de retour du service.
	switch($debut) {
		case TRUE:$debfinPhrase = 'debut'; break;
		case FALSE:$debfinPhrase = 'fin'; break;
		default:$debfinPhrase = 'debut';
	}

	$pluriel = ($duree > 1)?'s':'';

	switch($periodicite) {
		case '1':$periodicitePhrase = 'an'.$pluriel;break;
		case '2':$periodicitePhrase = 'semestre'.$pluriel;break;
		case '4':$periodicitePhrase = 'trimestre'.$pluriel;break;
		case '12':$periodicitePhrase = 'mois';break;
		default:$periodicitePhrase = 'mois';
	}

	$resultFormat = format_decimal($result);
	$tauxFormat = format_decimal($taux);

	$resultPhrase = $t."<div class='exempleResultat'><h4>R&eacute;sultat</h4><div class='res'>Un capital de $capital &#8364; plac&eacute; en $debfinPhrase de p&eacute;riode pendant $duree $periodicitePhrase au taux proportionnel annuel de $taux % s'&eacute;l&egrave;ve &agrave; <strong>$resultFormat &#8364;</strong>.  </div></div>";
	
	//Retour positif.
	return array($resultPhrase, $errorMessages);
}

/*---------------------------------------------------------------------------------------------------
 *  vasvc : Valeur acquise par une suite de versements constants 
 ----------------------------------------------------------------------------------------------------
 */
 
function vasvc($intVersements, $intNbVersements, $intPeriodicite, $blnDebut, $fltTaux) {

	$txM = 0.0;
	
	$txM = 1 + ($fltTaux / $intPeriodicite / 100);

	if($blnDebut == TRUE) $intNbVersements = $intNbVersements + 1;
	
	$result = 0;
	
	for ($i = 1; $i <= $intNbVersements; $i++)
		
	$result += ($intVersements * (pow($txM, $intNbVersements-$i)));
	
	return $result;
}

/*---------------------------------------------------------------------------------------------------
 *  vasvcService : Methode d'interface publique du protocole soap pour le service de vasvc
 ----------------------------------------------------------------------------------------------------
 */
 
function vasvcService($versements, $nbversements, $periodicite, $debut, $taux)  {

	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('Versements', $versements, '10000').
			checkInteger('Nombre de versements', $nbversements, '24').
			checkDecimal('Taux', $taux, '3.2');
	
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a &eacute;t&eacute; d&eacute;tect&eacute;e.', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.	
	$result = vasvc($versements, $nbversements, $periodicite, $debut, $taux);

	//Formattage de la phrase de retour du service.
	switch($debut) {
		case TRUE:$debfinPhrase = 'debut'; break;
		case FALSE:$debfinPhrase = 'fin'; break;
		default:$debfinPhrase = 'debut';
	}

	$pluriel = ($nbversements > 1)?'s':'';

	switch($periodicite) {
		case '1':$periodicitePhrase = 'annuel'.$pluriel;break;
		case '2':$periodicitePhrase = 'semestriel'.$pluriel;break;
		case '4':$periodicitePhrase = 'trimestriel'.$pluriel;break;
		default:$periodicitePhrase ='mensuel'.$pluriel;break;
	}

	$resultFormat = format_decimal($result);
	$tauxFormat = format_decimal($taux);

	$resultPhrase = "<div class='exempleResultat'><h4>R&eacute;sultat</h4><div class='res'>Le capital acquis au terme de $nbversements versements $periodicitePhrase de $versements euros plac&eacute;s en $debfinPhrase de p&eacute;riode au taux proportionnel annuel de $taux % s'&eacute;l&egrave;ve &agrave; <strong>$resultFormat &#8364;</strong>.  </div></div>";

	//Retour positif.
	return array($resultPhrase, $errorMessages);
}

/*---------------------------------------------------------------------------------------------------
 *  trc : Taux de rendement d'un capital 
 ----------------------------------------------------------------------------------------------------
 */
 
function trc($intCapital, $intPeriodicite, $intDuree, $blnDebut, $intCapitalAcquis) {

	switch($intPeriodicite) {
		case '1':$TauxB = 100;break;
		case '2':$TauxB = 50;break;
		case '4':$TauxB = 25;break;
		default:$TauxB = 10;break;
	}
	 
	if($blnDebut == TRUE)
	{
	$TauxA = 0;
		while (($TauxB - $TauxA) > 0.00000001)
		 {
		 $Result1=($TauxB + $TauxA)/2;
		 $Result2 = $intCapital * (pow((1+($Result1/100)), $intDuree)) * (1 + ($Result1/100));
		 
		 	if($Result2 < $intCapitalAcquis)
		 	{
		 	$TauxA = $Result1;
		 	}
		 	else
		 	{
		 	$TauxB = $Result1;
		 	}
		 }
	 }
	 else
	 {
	 $TauxA = 0;
		 while (($TauxB - $TauxA) > 0.00000001)
			 {
			 $Result1=($TauxB + $TauxA)/2;
			 $Result2 = $intCapital * (pow((1+($Result1/100)), $intDuree));
			 
			 	if($Result2 < $intCapitalAcquis)
			 	{
			 	$TauxA = $Result1;
			 	}
			 	else
			 	{
			 	$TauxB = $Result1;
			 	}
			 }
	 }
	$result = $Result1 * $intPeriodicite;
	$tresult = ((pow(1+($Result1/100), $intPeriodicite))-1)*100;
	return array($result,$tresult);
}

/*---------------------------------------------------------------------------------------------------
 *  trcService : Methode d'interface publique du protocole soap pour le service de trc
 ----------------------------------------------------------------------------------------------------
 */
 
function trcService($capital, $periodicite, $duree, $debut, $capitalacquis)  {

	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('Capital', $capital, '10000').
			checkInteger('Dur&eacute;e', $duree, '24');
			checkInteger('Capital acquis', $capitalacquis, '10000');
	
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a &eacute;t&eacute; d&eacute;tect&eacute;e.', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.
	list($result,$tresult) = trc($capital, $periodicite, $duree, $debut, $capitalacquis);

	//Formattage de la phrase de retour du service.
	switch($debut) {
		case TRUE:$debfinPhrase = 'debut'; break;
		case FALSE:$debfinPhrase = 'fin'; break;
		default:$debfinPhrase = 'debut';
	}

	$pluriel = ($duree > 1)?'s':'';

	switch($periodicite) {
		case '1':$periodicitePhrase = 'an'.$pluriel;break;
		case '2':$periodicitePhrase = 'semestre'.$pluriel;break;
		case '4':$periodicitePhrase = 'trimestre'.$pluriel;break;
		case '12':$periodicitePhrase = 'mois';break;
		default:$periodicitePhrase = 'mois';
	}

	$resultFormat = format_decimal($result);
	$tresultFormat = format_decimal($tresult);

	$resultPhrase = "<div class='exempleResultat'><h4>R&eacute;sultat</h4><div class='res'>Un capital de $capital euros plac&eacute; en $debfinPhrase de p&eacute;riode pendant $duree $periodicitePhrase s'&eacute;l&egrave;ve &agrave; $capitalacquis &#8364; si :<br> le taux du placement annuel proportionnel est de <strong>$resultFormat &#8364;</strong> soit un taux annuel &eacute;quivalent de <strong>$tresultFormat %</strong>.  </div></div>";
	
	//Retour positif.
	return array($resultPhrase, $errorMessages);
}

/*---------------------------------------------------------------------------------------------------
 *  trsvc : Taux de rendement d'une suite de versements constants 
 ----------------------------------------------------------------------------------------------------
 */
 
function trsvc($intVersements, $intNbVersements, $intPeriodicite, $blnDebut, $intCapitalAcquis) {

	switch($intPeriodicite) {
		case '1':$TauxB = 100;break;
		case '2':$TauxB = 50;break;
		case '4':$TauxB = 25;break;
		default:$TauxB = 10;break;
	}
	 
	if($blnDebut == TRUE)
	{
	$TauxA = 0;
		while (($TauxB - $TauxA) > 0.00000001)
		 {
		 $Result1=($TauxB + $TauxA)/2;
		 $Result2 = 0;
		 for ($i = 1; $i <= $intNbVersements; $i++)
		{	
			$Result2 = $Result2 + $intVersements * (pow((1+($Result1/100)), $intNbVersements-$i)) * (1 + ($Result1/100));
		}
				 
		 	if($Result2 < $intCapitalAcquis)
		 	{
		 	$TauxA = $Result1;
		 	}
		 	else
		 	{
		 	$TauxB = $Result1;
		 	}
		 }
	 }
	 else
	 {
	 $TauxA = 0;
		 while (($TauxB - $TauxA) > 0.00000001)
			 {
		 $Result1=($TauxB + $TauxA)/2;
		 $Result2 = 0;
		 for ($i = 1; $i <= $intNbVersements; $i++)
		{	
			$Result2 = $Result2 + $intVersements * (pow((1+($Result1/100)), $intNbVersements-$i));
		}
				 
		 	if($Result2 < $intCapitalAcquis)
		 	{
		 	$TauxA = $Result1;
		 	}
		 	else
		 	{
		 	$TauxB = $Result1;
		 	}
		 }
	 }
	$result = $Result1 * $intPeriodicite;
	$tresult = ((pow(1+($Result1/100), $intPeriodicite))-1)*100;
	return array($result,$tresult);
}

/*---------------------------------------------------------------------------------------------------
 *  trsvcService : Methode d'interface publique du protocole soap pour le service de trsvc
 ----------------------------------------------------------------------------------------------------
 */
 
function trsvcService($versements, $nbversements, $periodicite, $debut, $capitalacquis)  {

	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('Versements', $versements, '10000').
			checkInteger('Nombre de versements', $nbversements, '24').
			checkInteger('Capital acquis', $capitalacquis, '10000');
	
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a &eacute;t&eacute; d&eacute;tect&eacute;e.', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.	
	list($result,$tresult) = trsvc($versements, $nbversements, $periodicite, $debut, $capitalacquis);

	//Formattage de la phrase de retour du service.
	switch($debut) {
		case TRUE:$debfinPhrase = 'debut'; break;
		case FALSE:$debfinPhrase = 'fin'; break;
		default:$debfinPhrase = 'debut';
	}

	$pluriel = ($nbversements > 1)?'s':'';

	switch($periodicite) {
		case '1':$periodicitePhrase = 'annuel'.$pluriel;break;
		case '2':$periodicitePhrase = 'semestriel'.$pluriel;break;
		case '4':$periodicitePhrase = 'trimestriel'.$pluriel;break;
		default:$periodicitePhrase ='mensuel'.$pluriel;break;
	}

	$resultFormat = format_decimal($result);
	$tresultFormat = format_decimal($tresult);

	$resultPhrase = "<div class='exempleResultat'><h4>R&eacute;sultat</h4><div class='res'>Une suite de $nbversements versements $periodicitePhrase de $versements euros plac&eacute;s en $debfinPhrase de p&eacute;riode s'&eacute;l&egrave;ve &agrave; $capitalacquis &#8364; :<br>le taux du placement annuel proportionnel est de <strong>$resultFormat %</strong> soit un taux annuel &eacute;quivalent de <strong>$tresultFormat %</strong>.  </div></div>";	
	
	//Retour positif.
	return array($resultPhrase, $errorMessages);
}

/*---------------------------------------------------------------------------------------------------
 *  cce : Calcul du capital emprunté 
 ----------------------------------------------------------------------------------------------------
 */
 
function cce($intRemboursements, $intNbRemboursements, $intPeriodicite, $blnDebut, $fltTaux) {

	$txM = 0.0;
	$totvers = 0;
	$totamort = 0;
	$totint = 0;
		
	$tcapd = array();
	$tcapf = array();
	$tvers = array();
	$tint = array();
	$tamort = array();
	
	$txM = ($fltTaux / $intPeriodicite) / 100;

	if($blnDebut == TRUE) 
	{
	$result = ($intRemboursements * (1 + $txM) * (1 - pow(1 + $txM, -$intNbRemboursements))) / $txM;
	}
	else
	{
	$result = ($intRemboursements * (1 - pow(1 + $txM, -$intNbRemboursements))) / $txM;
	}
	
	//Tableau d'amortissement
    for($i=0; $i<$intNbRemboursements; $i++)
    {
    	if($i==0)
    	{
			$tcapd[$i] = $result;
    	}
    	else
    	{
    		$tcapd[$i] = $tcapf[$i-1];
    	}
    	$tvers[$i] = $intRemboursements;

    		if($blnDebut == FALSE)
    		{
    			$tint[$i] = $tcapd[$i] * $txM;
    		}
    		else
    		{
    			$tint[$i]= ($tcapd[$i] - $tvers[$i]) * $txM;
    		}
    	
    	if($i==($intNbRemboursements-1))
    	{
    		$tcapf[$i] = 0;
    		$tamort[$i] = $tcapd[$i];
    		$tint[$i] = $tvers[$i] - $tcapd[$i];
    	}
    	
    	$tamort[$i] = $tvers[$i] - $tint[$i];
    	$tcapf[$i] = $tcapd[$i] - $tamort[$i];
    	$totvers = $totvers + $tvers[$i];
    	$totamort = $totamort + $tamort[$i];
    	$totint = $totint + $tint[$i];
    }
    
    return array($result, $tcapd, $tvers, $tint, $tamort, $tcapf, $totvers, $totamort, $totint);
	
}

/*---------------------------------------------------------------------------------------------------
 *  cceService : Methode d'interface publique du protocole soap pour le service de cce
 ----------------------------------------------------------------------------------------------------
 */
 
function cceService($remboursements, $nbremboursements, $periodicite, $debut, $taux)  {

	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('Remboursements', $remboursements, '10000').
			checkInteger('Nombre de remboursements', $nbremboursements, '24').
			checkDecimal('Taux', $taux, '3.2');
	
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a &eacute;t&eacute; d&eacute;tect&eacute;e.', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.	
	list($result, $tcapd, $tvers, $tint, $tamort, $tcapf, $totvers, $totamort, $totint) = cce($remboursements, $nbremboursements, $periodicite, $debut, $taux);

	//Formattage de la phrase de retour du service.
	switch($debut) {
		case TRUE:$debfinPhrase = 'debut'; break;
		case FALSE:$debfinPhrase = 'fin'; break;
		default:$debfinPhrase = 'debut';
	}

	$pluriel = ($nbremboursements > 1)?'s':'';

	switch($periodicite) {
		case '1':$periodicitePhrase = 'annuel'.$pluriel;break;
		case '2':$periodicitePhrase = 'semestriel'.$pluriel;break;
		case '4':$periodicitePhrase = 'trimestriel'.$pluriel;break;
		default:$periodicitePhrase ='mensuel'.$pluriel;break;
	}

	$resultFormat = format_decimal($result);
	$tauxFormat = format_decimal($taux);
	
	$resultPhrase = "<div class='exempleResultat'><h4>R&eacute;sultat</h4><div class='res'>Le capital d'un emprunt rembours&eacute; sur $nbremboursements versements $periodicitePhrase au taux proportionnel annuel (frais inclus) de $taux % avec des versements constants de $remboursements &#8364; s'effectuant en $debfinPhrase de p&eacute;riode s'&eacute;l&egrave;ve &agrave; <strong>$resultFormat &#8364;</strong>.  </div></div>";
	$resultPhrase = $resultPhrase . "<table border='1' cellpadding='2' cellspacing='0' width='100%' class='table1'>";
	$resultPhrase = $resultPhrase . "<Caption>Tableau d'amortissement</Caption>";
	$resultPhrase = $resultPhrase . "<thead>";
	$resultPhrase = $resultPhrase . "<tr>";
	$resultPhrase = $resultPhrase . "<th id='thTitre1'>Ech&eacute;ance</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre2'>Capital d&eacute;but</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre3'>Versements</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre4'>Int&eacute;r&ecirc;ts</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre5'>Amortissem.</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre6'>Capital fin</th>";
	$resultPhrase = $resultPhrase . "</tr>";
	$resultPhrase = $resultPhrase . "</thead>";
	$resultPhrase = $resultPhrase . "<tbody>";
		
	for($i=0; $i<$nbremboursements; $i++)
	{	
		$resultPhrase = $resultPhrase ."<TR>";
		$resultPhrase = $resultPhrase ."<td headers='thTitre1'>";	
		$resultPhrase = $resultPhrase .($i+1);	
		$resultPhrase = $resultPhrase ."</td>";	
		$resultPhrase = $resultPhrase ."<td headers='thTitre2'>";	
		$resultPhrase = $resultPhrase .format_decimal($tcapd[$i]);
		$resultPhrase = $resultPhrase ."</td>";	
		$resultPhrase = $resultPhrase ."<td headers='thTitre3'>";	
		$resultPhrase = $resultPhrase .format_decimal($tvers[$i]);
		$resultPhrase = $resultPhrase ."</td>";	
		$resultPhrase = $resultPhrase ."<td headers='thTitre4'>";	
		$resultPhrase = $resultPhrase .format_decimal($tint[$i]);
		$resultPhrase = $resultPhrase ."</td>";	
		$resultPhrase = $resultPhrase ."<td headers='thTitre5'>";	
		$resultPhrase = $resultPhrase .format_decimal($tamort[$i]);
		$resultPhrase = $resultPhrase ."</td>";	
		$resultPhrase = $resultPhrase ."<td headers='thTitre6'>";	
		$resultPhrase = $resultPhrase .format_decimal($tcapf[$i]);
		$resultPhrase = $resultPhrase ."</td>";	
		$resultPhrase = $resultPhrase ."</TR>";	
	}
	
	$resultPhrase = $resultPhrase ."<TR>";
	$resultPhrase = $resultPhrase ."<td headers='thTitre1'>TOTAL</td>";	
	$resultPhrase = $resultPhrase ."<td headers='thVide'></td>";	
	$resultPhrase = $resultPhrase ."<td headers='thTitre3'>";	
	$resultPhrase = $resultPhrase .format_decimal($totvers);
	$resultPhrase = $resultPhrase ."</td>";	
	$resultPhrase = $resultPhrase ."<td headers='thTitre4'>";
	$resultPhrase = $resultPhrase .format_decimal($totint);
	$resultPhrase = $resultPhrase ."</td>";		
	$resultPhrase = $resultPhrase ."<td headers='thTitre5'>";	
	$resultPhrase = $resultPhrase .format_decimal($totamort);
	$resultPhrase = $resultPhrase ."</td>";		
	$resultPhrase = $resultPhrase ."<td headers='thVide'></td>";	
	$resultPhrase = $resultPhrase ."</TR>";	
	$resultPhrase = $resultPhrase . "</tbody>";
	$resultPhrase = $resultPhrase ."</TABLE>";	
	
	//Retour positif.
	return array($resultPhrase, $errorMessages);
}

/*---------------------------------------------------------------------------------------------------
 *  cd : Calcul de la durée 
 ----------------------------------------------------------------------------------------------------
 */
 
function cd($intCapital, $intRemboursements, $intPeriodicite, $blnDebut, $fltTaux) {

	$txM = 0.0;
	$duree = 0;
	$totvers = 0;
	$totamort = 0;
	$totint = 0;
		
	$tcapd = array();
	$tcapf = array();
	$tvers = array();
	$tint = array();
	$tamort = array();
	
	$txM = ($fltTaux / $intPeriodicite) / 100;

	if($blnDebut == TRUE) 
	{
	$result1 = $intRemboursements * (1 + $txM) / ((-$intCapital * $txM) + $intRemboursements * (1 + $txM));
	$result2 = 1 + $txM;
	}
	else
	{
	$result1 = $intRemboursements / ((-$intCapital * $txM) + $intRemboursements);
	$result2 = 1 + $txM;
	}
	
	if ($result1 >= 0)
	{
		if ($result2 >= 0)
		{
		$duree = log($result1) / log($result2);
		}
	}
	
	//Tableau d'amortissement

    $dureeFormat = number_format($duree);
    
    for($i=0; $i<$dureeFormat; $i++)
    {
    	if($i==0)
    	{
			$tcapd[$i] = $intCapital;
    	}
    	else
    	{
    		$tcapd[$i] = $tcapf[$i-1];
    	}
    	$tvers[$i] = $intRemboursements;

    		if($blnDebut == FALSE)
    		{
    			$tint[$i] = $tcapd[$i] * $txM;
    		}
    		else
    		{
    			$tint[$i]= ($tcapd[$i] - $tvers[$i]) * $txM;
    		}
    	
    	if($i==($dureeFormat-1))
    	{
    		$tcapf[$i] = 0;
    		$tamort[$i] = $tcapd[$i];
    		$tint[$i] = $tvers[$i] - $tcapd[$i];
    	}
    	
    	$tamort[$i] = $tvers[$i] - $tint[$i];
    	$tcapf[$i] = $tcapd[$i] - $tamort[$i];
    	$totvers = $totvers + $tvers[$i];
    	$totamort = $totamort + $tamort[$i];
    	$totint = $totint + $tint[$i];
    }
    
    return array($duree, $tcapd, $tvers, $tint, $tamort, $tcapf, $totvers, $totamort, $totint);
	
}

/*---------------------------------------------------------------------------------------------------
 *  cdService : Methode d'interface publique du protocole soap pour le service de cd
 ----------------------------------------------------------------------------------------------------
 */
 
function cdService($capital, $remboursements, $periodicite, $debut, $taux)  {

	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('Capital', $capital, '10000').
			checkInteger('Remboursements', $remboursements, '10000').
			checkDecimal('Taux', $taux, '3.2');
	
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a &eacute;t&eacute; d&eacute;tect&eacute;e.', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.	
	list($duree, $tcapd, $tvers, $tint, $tamort, $tcapf, $totvers, $totamort, $totint) = cd($capital, $remboursements, $periodicite, $debut, $taux);

	//Formattage de la phrase de retour du service.
	switch($debut) {
		case TRUE:$debfinPhrase = 'debut'; break;
		case FALSE:$debfinPhrase = 'fin'; break;
		default:$debfinPhrase = 'debut';
	}

	$pluriel = ($duree > 1)?'s':'';

	switch($periodicite) {
		case '1':$periodicitePhrase = 'an'.$pluriel;break;
		case '2':$periodicitePhrase = 'semestre'.$pluriel;break;
		case '4':$periodicitePhrase = 'trimestre'.$pluriel;break;
		case '12':$periodicitePhrase = 'mois';break;
		default:$periodicitePhrase = 'mois';
	}

	$dureeFormat = number_format($duree);
	$tauxFormat = format_decimal($taux);

	$resultPhrase = "<div class='exempleResultat'><h4>R&eacute;sultat</h4><div class='res'>Un capital emprunt&eacute; de $capital &#8364 rembours&eacute; au taux proportionnel annuel (frais inclus) de $taux % avec des versements constants de $remboursements &#8364 s'effectuant en $debfinPhrase de p&eacute;riode aura une dur&eacute;e de <strong>$dureeFormat $periodicitePhrase</strong>.  </div></div>";
		
	$resultPhrase = $resultPhrase . "<table border='1' cellpadding='2' cellspacing='0' width='100%' class='table1'>";
	$resultPhrase = $resultPhrase . "<Caption>Tableau d'amortissement</Caption>";
	$resultPhrase = $resultPhrase . "<thead>";
	$resultPhrase = $resultPhrase . "<tr>";
	$resultPhrase = $resultPhrase . "<th id='thTitre1'>Ech&eacute;ance</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre2'>Capital d&eacute;but</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre3'>Versements</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre4'>Int&eacute;r&ecirc;ts</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre5'>Amortissem.</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre6'>Capital fin</th>";
	$resultPhrase = $resultPhrase . "</tr>";
	$resultPhrase = $resultPhrase . "</thead>";
	$resultPhrase = $resultPhrase . "<tbody>";
		
	for($i=0; $i<$dureeFormat; $i++)
	{	
		$resultPhrase = $resultPhrase ."<TR>";
		$resultPhrase = $resultPhrase ."<TD align=center valign=middle>";	
		$resultPhrase = $resultPhrase .($i+1);	
		$resultPhrase = $resultPhrase ."<TD align=right valign=middle>";	
		$resultPhrase = $resultPhrase .format_decimal($tcapd[$i]);
		$resultPhrase = $resultPhrase ."<TD align=right valign=middle>";	
		$resultPhrase = $resultPhrase .format_decimal($tvers[$i]);
		$resultPhrase = $resultPhrase ."<TD align=right valign=middle>";	
		$resultPhrase = $resultPhrase .format_decimal($tint[$i]);
		$resultPhrase = $resultPhrase ."<TD align=right valign=middle>";	
		$resultPhrase = $resultPhrase .format_decimal($tamort[$i]);
		$resultPhrase = $resultPhrase ."<TD align=right valign=middle>";	
		$resultPhrase = $resultPhrase .format_decimal($tcapf[$i]);
		$resultPhrase = $resultPhrase ."</TR>";	
	}

		$resultPhrase = $resultPhrase ."<TR>";
	$resultPhrase = $resultPhrase ."<td headers='thTitre1'>TOTAL</td>";	
	$resultPhrase = $resultPhrase ."<td headers='thVide'></td>";	
	$resultPhrase = $resultPhrase ."<td headers='thTitre3'>";	
	$resultPhrase = $resultPhrase .format_decimal($totvers);
	$resultPhrase = $resultPhrase ."</td>";	
	$resultPhrase = $resultPhrase ."<td headers='thTitre4'>";
	$resultPhrase = $resultPhrase .format_decimal($totint);
	$resultPhrase = $resultPhrase ."</td>";		
	$resultPhrase = $resultPhrase ."<td headers='thTitre5'>";	
	$resultPhrase = $resultPhrase .format_decimal($totamort);
	$resultPhrase = $resultPhrase ."</td>";		
	$resultPhrase = $resultPhrase ."<td headers='thVide'></td>";	
	$resultPhrase = $resultPhrase ."</TR>";	
	$resultPhrase = $resultPhrase . "</tbody>";
	$resultPhrase = $resultPhrase ."</TABLE>";	
	
	//Retour positif.
	return array($resultPhrase, $errorMessages);
}

/*---------------------------------------------------------------------------------------------------
 *  cvp : Calcul du versement périodique
 ----------------------------------------------------------------------------------------------------
 */
 
function cvp($intCapital, $intNbRemboursements, $intPeriodicite, $blnDebut, $fltTaux) {

	$txM = 0.0;
	$totvers = 0;
	$totamort = 0;
	$totint = 0;
		
	$tcapd = array();
	$tcapf = array();
	$tvers = array();
	$tint = array();
	$tamort = array();
	
	$txM = ($fltTaux / $intPeriodicite) / 100;

	if($blnDebut == TRUE) 
	{
	$result = $intCapital * $txM / (1 - pow(1 + $txM, -$intNbRemboursements)) * (1 / (1 + $txM));
	}
	else
	{
	$result = $intCapital * $txM / (1 - pow(1 + $txM, -$intNbRemboursements));
	}
	
		
	//Tableau d'amortissement

    for($i=0; $i<$intNbRemboursements; $i++)
    {
    	if($i==0)
    	{
			$tcapd[$i] = $intCapital;
    	}
    	else
    	{
    		$tcapd[$i] = $tcapf[$i-1];
    	}
    	$tvers[$i] = $result;

    		if($blnDebut == FALSE)
    		{
    			$tint[$i] = $tcapd[$i] * $txM;
    		}
    		else
    		{
    			$tint[$i]= ($tcapd[$i] - $tvers[$i]) * $txM;
    		}
    	
    	if($i==($intNbRemboursements-1))
    	{
    		$tcapf[$i] = 0;
    		$tamort[$i] = $tcapd[$i];
    		$tint[$i] = $tvers[$i] - $tcapd[$i];
    	}
    	
    	$tamort[$i] = $tvers[$i] - $tint[$i];
    	$tcapf[$i] = $tcapd[$i] - $tamort[$i];
    	$totvers = $totvers + $tvers[$i];
    	$totamort = $totamort + $tamort[$i];
    	$totint = $totint + $tint[$i];
    }
    
    return array($result, $tcapd, $tvers, $tint, $tamort, $tcapf, $totvers, $totamort, $totint);
	
}

/*---------------------------------------------------------------------------------------------------
 *  cvpService : Methode d'interface publique du protocole soap pour le service de cvp
 ----------------------------------------------------------------------------------------------------
 */
 
function cvpService($capital, $nbremboursements, $periodicite, $debut, $taux)  {

	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('Capital', $capital, '10000').
			checkInteger('Nombre de remboursements', $nbremboursements, '48').
			checkDecimal('Taux', $taux, '3.2');
	
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a &eacute;t&eacute; d&eacute;tect&eacute;e.', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.	
	list($result, $tcapd, $tvers, $tint, $tamort, $tcapf, $totvers, $totamort, $totint) = cvp($capital, $nbremboursements, $periodicite, $debut, $taux);

	//Formattage de la phrase de retour du service.
	switch($debut) {
		case TRUE:$debfinPhrase = 'debut'; break;
		case FALSE:$debfinPhrase = 'fin'; break;
		default:$debfinPhrase = 'debut';
	}

	$pluriel = ($nbremboursements > 1)?'s':'';

	switch($periodicite) {
		case '1':$periodicitePhrase = 'an'.$pluriel;break;
		case '2':$periodicitePhrase = 'semestre'.$pluriel;break;
		case '4':$periodicitePhrase = 'trimestre'.$pluriel;break;
		case '12':$periodicitePhrase = 'mois';break;
		default:$periodicitePhrase = 'mois';
	}

	$resultFormat = format_decimal($result);
	$tauxFormat = format_decimal($taux);
	
	$resultPhrase = "<div class='exempleResultat'><h4>R&eacute;sultat</h4><div class='res'>Le montant des versements d'un emprunt de $capital &#8364 dont les remboursements s'effectuent en $debfinPhrase sur une dur&eacute;e de $nbremboursements $periodicitePhrase et soumis au taux proportionnel annuel de $tauxFormat % est de <strong>$resultFormat &#8364</strong>.  </div></div>";	
	
	$resultPhrase = $resultPhrase . "<table border='1' cellpadding='2' cellspacing='0' width='100%' class='table1'>";
	$resultPhrase = $resultPhrase . "<Caption>Tableau d'amortissement</Caption>";
	$resultPhrase = $resultPhrase . "<thead>";
	$resultPhrase = $resultPhrase . "<tr>";
	$resultPhrase = $resultPhrase . "<th id='thTitre1'>Ech&eacute;ance</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre2'>Capital d&eacute;but</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre3'>Versements</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre4'>Int&eacute;r&ecirc;ts</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre5'>Amortissem.</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre6'>Capital fin</th>";
	$resultPhrase = $resultPhrase . "</tr>";
	$resultPhrase = $resultPhrase . "</thead>";
	$resultPhrase = $resultPhrase . "<tbody>";
		
	for($i=0; $i<$nbremboursements; $i++)
	{	
		$resultPhrase = $resultPhrase ."<TR>";
		$resultPhrase = $resultPhrase ."<TD align=center valign=middle>";	
		$resultPhrase = $resultPhrase .($i+1);	
		$resultPhrase = $resultPhrase ."<TD align=right valign=middle>";	
		$resultPhrase = $resultPhrase .format_decimal($tcapd[$i]);
		$resultPhrase = $resultPhrase ."<TD align=right valign=middle>";	
		$resultPhrase = $resultPhrase .format_decimal($tvers[$i]);
		$resultPhrase = $resultPhrase ."<TD align=right valign=middle>";	
		$resultPhrase = $resultPhrase .format_decimal($tint[$i]);
		$resultPhrase = $resultPhrase ."<TD align=right valign=middle>";	
		$resultPhrase = $resultPhrase .format_decimal($tamort[$i]);
		$resultPhrase = $resultPhrase ."<TD align=right valign=middle>";	
		$resultPhrase = $resultPhrase .format_decimal($tcapf[$i]);
		$resultPhrase = $resultPhrase ."</TR>";	
	}
	$resultPhrase = $resultPhrase ."<TR>";
	$resultPhrase = $resultPhrase ."<td headers='thTitre1'>TOTAL</td>";	
	$resultPhrase = $resultPhrase ."<td headers='thVide'></td>";	
	$resultPhrase = $resultPhrase ."<td headers='thTitre3'>";	
	$resultPhrase = $resultPhrase .format_decimal($totvers);
	$resultPhrase = $resultPhrase ."</td>";	
	$resultPhrase = $resultPhrase ."<td headers='thTitre4'>";
	$resultPhrase = $resultPhrase .format_decimal($totint);
	$resultPhrase = $resultPhrase ."</td>";		
	$resultPhrase = $resultPhrase ."<td headers='thTitre5'>";	
	$resultPhrase = $resultPhrase .format_decimal($totamort);
	$resultPhrase = $resultPhrase ."</td>";		
	$resultPhrase = $resultPhrase ."<td headers='thVide'></td>";	
	$resultPhrase = $resultPhrase ."</TR>";	
	$resultPhrase = $resultPhrase . "</tbody>";
	$resultPhrase = $resultPhrase ."</TABLE>";	
	
	//Retour positif.
	return array($resultPhrase, $errorMessages);
}

/*---------------------------------------------------------------------------------------------------
 *  ct : Calcul du taux
 ----------------------------------------------------------------------------------------------------
 */
 
function ct($intCapital, $intVersements, $intNbVersements, $intPeriodicite, $blnDebut) {

	$totvers = 0;
	$totamort = 0;
	$totint = 0;
		
	$tcapd = array();
	$tcapf = array();
	$tvers = array();
	$tint = array();
	$tamort = array();
	
	switch($intPeriodicite) {
		case '1':$TauxB = 100;break;
		case '2':$TauxB = 50;break;
		case '4':$TauxB = 25;break;
		default:$TauxB = 10;break;
	}
	  
	if($blnDebut == TRUE)
	{
	$TauxA = 0;
		while (($TauxB - $TauxA) > 0.00000001)
		 {
		 $Result1=($TauxB + $TauxA)/2;
		 $Result2 = 0;
		 for ($i = 1; $i <= $intNbVersements; $i++)
		{	
			$Result2 = $Result2 + ($intVersements / (pow(1+($Result1/100), $i)) * (1 + $Result1/100));
		}
		 	if($Result2 < $intCapital)
		 	{
		 	$TauxA = $Result1;
		 	}
		 	else
		 	{
		 	$TauxB = $Result1;
		 	}
		 }
	 }
	 else
	 {
	 $TauxA = 0;
		 while (($TauxB - $TauxA) > 0.00000001)
			 {
		 $Result1=($TauxB + $TauxA)/2;
		 $Result2 = 0;
		 for ($i = 1; $i <= $intNbVersements; $i++)
		{	
			$Result2 = $Result2 + ($intVersements / (pow(1+($Result1/100), $i)));
		}
				 
		 	if($Result2 > $intCapital)
		 	{
		 	$TauxA = $Result1;
		 	}
		 	else
		 	{
		 	$TauxB = $Result1;
		 	}
		 }
	 }
	$result = $Result1 * $intPeriodicite;
	$tresult = ((pow(1+($Result1/100), $intPeriodicite))-1)*100;
	
	$txM = ($result / $intPeriodicite) / 100;	
	//Tableau d'amortissement
	
    for($i=0; $i<$intNbVersements; $i++)
    {
    	if($i==0)
    	{
			$tcapd[$i] = $intCapital;
    	}
    	else
    	{
    		$tcapd[$i] = $tcapf[$i-1];
    	}
    	$tvers[$i] = $intVersements;

    		if($blnDebut == FALSE)
    		{
    			$tint[$i] = $tcapd[$i] * $txM;
    		}
    		else
    		{
    			$tint[$i]= ($tcapd[$i] - $tvers[$i]) * $txM;
    		}
    	
    	if($i==($intNbVersements-1))
    	{
    		$tcapf[$i] = 0;
    		$tamort[$i] = $tcapd[$i];
    		$tint[$i] = $tvers[$i] - $tcapd[$i];
    	}
    	
    	$tamort[$i] = $tvers[$i] - $tint[$i];
    	$tcapf[$i] = $tcapd[$i] - $tamort[$i];
    	$totvers = $totvers + $tvers[$i];
    	$totamort = $totamort + $tamort[$i];
    	$totint = $totint + $tint[$i];
    }
    
    return array($result, $tresult, $tcapd, $tvers, $tint, $tamort, $tcapf, $totvers, $totamort, $totint);
	
}

/*---------------------------------------------------------------------------------------------------
 *  ctService : Methode d'interface publique du protocole soap pour le service de ct
 ----------------------------------------------------------------------------------------------------
 */
 
function ctService($capital, $versements, $nbversements, $periodicite, $debut)  {

	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('Capital', $capital, '10000').
			checkInteger('Montant des versements', $versements, '10000').
			checkInteger('Nombre de versements', $nbversements, '48');
	
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a &eacute;t&eacute; d&eacute;tect&eacute;e.', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.	
	list($result, $tresult, $tcapd, $tvers, $tint, $tamort, $tcapf, $totvers, $totamort, $totint) = ct($capital, $versements, $nbversements, $periodicite, $debut);

	//Formattage de la phrase de retour du service.
	switch($debut) {
		case TRUE:$debfinPhrase = 'debut'; break;
		case FALSE:$debfinPhrase = 'fin'; break;
		default:$debfinPhrase = 'debut';
	}

	$pluriel = ($nbversements > 1)?'s':'';

	switch($periodicite) {
		case '1':$periodicitePhrase = 'an'.$pluriel;break;
		case '2':$periodicitePhrase = 'semestre'.$pluriel;break;
		case '4':$periodicitePhrase = 'trimestre'.$pluriel;break;
		case '12':$periodicitePhrase = 'mois';break;
		default:$periodicitePhrase = 'mois';
	}

	$resultFormat = format_decimal($result);
	$tresultFormat = format_decimal($tresult);
	
	$resultPhrase = "<div class='exempleResultat'><h4>R&eacute;sultat</h4><div class='res'>Un capital emprunt&eacute; de $capital &#8364; rembours&eacute; sur $nbversements $periodicitePhrase avec des versements constants de $versements &#8364;, s'effectuant en $debfinPhrase de p&eacute;riode est soumis au taux effectif suivant : <br>Taux annuel proportionnel (frais inclus) <strong>$resultFormat %</strong>, soit un taux annuel &eacute;quivalent (frais inclus) de <strong>$tresultFormat %</strong>.  </div></div>";		
	
	$resultPhrase = $resultPhrase . "<table border='1' cellpadding='2' cellspacing='0' width='100%' class='table1'>";
	$resultPhrase = $resultPhrase . "<Caption>Tableau d'amortissement</Caption>";
	$resultPhrase = $resultPhrase . "<thead>";
	$resultPhrase = $resultPhrase . "<tr>";
	$resultPhrase = $resultPhrase . "<th id='thTitre1'>Ech&eacute;ance</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre2'>Capital d&eacute;but</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre3'>Versements</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre4'>Int&eacute;r&ecirc;ts</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre5'>Amortissem.</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre6'>Capital fin</th>";
	$resultPhrase = $resultPhrase . "</tr>";
	$resultPhrase = $resultPhrase . "</thead>";
	$resultPhrase = $resultPhrase . "<tbody>";
		
	for($i=0; $i<$nbversements; $i++)
	{	
		$resultPhrase = $resultPhrase ."<TR>";
		$resultPhrase = $resultPhrase ."<TD align=center valign=middle>";	
		$resultPhrase = $resultPhrase .($i+1);	
		$resultPhrase = $resultPhrase ."<TD align=right valign=middle>";	
		$resultPhrase = $resultPhrase .format_decimal($tcapd[$i]);
		$resultPhrase = $resultPhrase ."<TD align=right valign=middle>";	
		$resultPhrase = $resultPhrase .format_decimal($tvers[$i]);
		$resultPhrase = $resultPhrase ."<TD align=right valign=middle>";	
		$resultPhrase = $resultPhrase .format_decimal($tint[$i]);
		$resultPhrase = $resultPhrase ."<TD align=right valign=middle>";	
		$resultPhrase = $resultPhrase .format_decimal($tamort[$i]);
		$resultPhrase = $resultPhrase ."<TD align=right valign=middle>";	
		$resultPhrase = $resultPhrase .format_decimal($tcapf[$i]);
		$resultPhrase = $resultPhrase ."</TR>";	
	}
	
	$resultPhrase = $resultPhrase ."<TR>";
	$resultPhrase = $resultPhrase ."<td headers='thTitre1'>TOTAL</td>";	
	$resultPhrase = $resultPhrase ."<td headers='thVide'></td>";	
	$resultPhrase = $resultPhrase ."<td headers='thTitre3'>";	
	$resultPhrase = $resultPhrase .format_decimal($totvers);
	$resultPhrase = $resultPhrase ."</td>";	
	$resultPhrase = $resultPhrase ."<td headers='thTitre4'>";
	$resultPhrase = $resultPhrase .format_decimal($totint);
	$resultPhrase = $resultPhrase ."</td>";		
	$resultPhrase = $resultPhrase ."<td headers='thTitre5'>";	
	$resultPhrase = $resultPhrase .format_decimal($totamort);
	$resultPhrase = $resultPhrase ."</td>";		
	$resultPhrase = $resultPhrase ."<td headers='thVide'></td>";	
	$resultPhrase = $resultPhrase ."</TR>";	
	$resultPhrase = $resultPhrase . "</tbody>";
	$resultPhrase = $resultPhrase ."</TABLE>";	
	
	//Retour positif.
	return array($resultPhrase, $errorMessages);
}

/*---------------------------------------------------------------------------------------------------
 *  cl : Calcul des loyers 
 ----------------------------------------------------------------------------------------------------
 */
 
function cl($intMontant, $intDepot, $intNbLoyers, $intPeriodicite, $blnDebut, $intValeur, $fltTaux) {

	$txM = 0.0;
	
	$txM = ($fltTaux / $intPeriodicite / 100);

	if($blnDebut == TRUE) 
	{
	$result = ($intMontant - $intDepot - ( -$intDepot + $intValeur) * (pow (1 + $txM, -$intNbLoyers))) * ($txM / (1 - (pow (1 + $txM, -$intNbLoyers)))) * (1 / (1+$txM));
	}
	else
	{
	$result = ($intMontant - $intDepot - ( -$intDepot + $intValeur) * (pow (1 + $txM, -$intNbLoyers))) * ($txM / (1 - (pow (1 + $txM, -$intNbLoyers))));
	}
	
	return $result;
}

/*---------------------------------------------------------------------------------------------------
 *  clService : Methode d'interface publique du protocole soap pour le service de cl
 ----------------------------------------------------------------------------------------------------
 */
 
function clService($montant, $depot, $nbloyers, $periodicite, $debut, $valeur, $taux)  {

	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('Montant', $montant, '10000').
			checkInteger('D&eacute;p&ocirc;t', $depot, '10000').
			checkInteger('Nombres de loyers', $nbloyers, '48').
			checkInteger('Valeur', $valeur, '10000').
			checkDecimal('Taux', $taux, '3.2').
			calculLoyersMV($montant,$valeur).
			calculLoyersMD($montant,$depot);
	
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a &eacute;t&eacute; d&eacute;tect&eacute;e.', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.	
	$result = cl($montant, $depot, $nbloyers, $periodicite, $debut, $valeur, $taux);

	//Formattage de la phrase de retour du service.
	switch($debut) {
		case TRUE:$debfinPhrase = 'debut'; break;
		case FALSE:$debfinPhrase = 'fin'; break;
		default:$debfinPhrase = 'debut';
	}

	$pluriel = ($nbloyers > 1)?'s':'';

	switch($periodicite) {
		case '1':$periodicitePhrase = 'an'.$pluriel;break;
		case '2':$periodicitePhrase = 'semestre'.$pluriel;break;
		case '4':$periodicitePhrase = 'trimestre'.$pluriel;break;
		case '12':$periodicitePhrase = 'mois';break;
		default:$periodicitePhrase = 'mois';
	}

	$resultFormat = format_decimal($result);
	$tauxFormat = format_decimal($taux);

	$resultPhrase = "<div class='exempleResultat'><h4>R&eacute;sultat</h4><div class='res'>Le montant des loyers d'un cr&eacute;dit-bail de $montant &#8364;, avec un d&eacute;p&ocirc;t de garantie de $depot &#8364; et une valeur de rachat de $valeur &#8364;, dont les remboursements s'effectuent en $debfinPhrase de p&eacute;riode sur une dur&eacute;e de $nbloyers $periodicitePhrase et soumis au taux proportionnel de $tauxFormat % est de <strong>$resultFormat &#8364;</strong>.  </div></div>";	
	
	//Retour positif.
	return array($resultPhrase, $errorMessages);
}

/*---------------------------------------------------------------------------------------------------
 *  ctr : Calcul du taux réel 
 ----------------------------------------------------------------------------------------------------
 */
 
function ctr($intMontant, $intDepot, $intRemboursements, $intNbLoyers, $intPeriodicite, $blnDebut, $intValeur) {

	switch($intPeriodicite) {
		case '1':$TauxB = 100;break;
		case '2':$TauxB = 50;break;
		case '4':$TauxB = 25;break;
		default:$TauxB = 10;break;
	}
	  
	if($blnDebut == TRUE)
	{
	$TauxA = 0;
	
		while (($TauxB - $TauxA) > 0.00000001)
		 {
		 $Result1=($TauxB + $TauxA)/2;
		 $Result2 = 0;
		 for ($i = 1; $i <= $intNbLoyers; $i++)
			{	
				$Result2 = $Result2 + ($intRemboursements / (pow(1+($Result1/100), $i)) * (1 + $Result1 /100));
			}
		
		$TauxT = $Result1 / 100;
		$Var1 = $intValeur * (pow($TauxT + 1, -$intNbLoyers));
		$Var2 = $intDepot * (pow($TauxT + 1, -$intNbLoyers));
		$Result2 = $Result2 + $Var1 - $Var2 + $intDepot;
		
		 	if($Result2 > $intMontant)
		 	{
		 	$TauxA = $Result1;
		 	}
		 	else
		 	{
		 	$TauxB = $Result1;
		 	}
		 }
	 }
	 else
	 {
	 $TauxA = 0;
		 while (($TauxB - $TauxA) > 0.00000001)
			 {
		 $Result1=($TauxB + $TauxA)/2;
		 $Result2 = 0;
		 for ($i = 1; $i <= $intNbLoyers; $i++)
		{	
			$Result2 = $Result2 + ($intRemboursements / (pow(1+($Result1/100), $i)));
		}
		
		$TauxT = $Result1 / 100;
		$Var1 = $intValeur * (pow($TauxT + 1, -$intNbLoyers));
		$Var2 = $intDepot * (pow($TauxT + 1, -$intNbLoyers));
		$Result2 = $Result2 + $Var1 - $Var2 + $intDepot;
				 
		 	if($Result2 > $intMontant)
		 	{
		 	$TauxA = $Result1;
		 	}
		 	else
		 	{
		 	$TauxB = $Result1;
		 	}
		 }
	 }
	$result = $Result1 * $intPeriodicite;
	$tresult = ((pow(1+($Result1/100), $intPeriodicite))-1)*100;
	
	return array($result,$tresult);
}

/*---------------------------------------------------------------------------------------------------
 *  ctrService : Methode d'interface publique du protocole soap pour le service de ctr
 ----------------------------------------------------------------------------------------------------
 */
 

function ctrService($montant, $depot, $remboursements, $nbloyers, $periodicite, $debut, $valeur)  {

	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('Montant', $montant, '10000').
			checkInteger('D&eacute;p&ocirc;t', $depot, '10000').
			checkInteger('Remboursements', $remboursements, '10000').
			checkInteger('Nombres de loyers', $nbloyers, '48').
			checkInteger('Valeur', $valeur, '10000').
			calculLoyersMV($montant,$valeur).
			calculLoyersMD($montant,$depot);
	
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a &eacute;t&eacute; d&eacute;tect&eacute;e.', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.	
	list($result,$tresult) = ctr($montant, $depot, $remboursements, $nbloyers, $periodicite, $debut, $valeur);

	//Formattage de la phrase de retour du service.
	switch($debut) {
		case TRUE:$debfinPhrase = 'debut'; break;
		case FALSE:$debfinPhrase = 'fin'; break;
		default:$debfinPhrase = 'debut';
	}

	$pluriel = ($nbloyers > 1)?'s':'';

	switch($periodicite) {
		case '1':$periodicitePhrase = 'an'.$pluriel;break;
		case '2':$periodicitePhrase = 'semestre'.$pluriel;break;
		case '4':$periodicitePhrase = 'trimestre'.$pluriel;break;
		case '12':$periodicitePhrase = 'mois';break;
		default:$periodicitePhrase = 'mois';
	}

	$resultFormat = format_decimal($result);
	$tresultFormat = format_decimal($tresult);

	$resultPhrase = "<div class='exempleResultat'><h4>R&eacute;sultat</h4><div class='res'>Un capital de $montant &#8364; financ&eacute; en cr&eacute;dit-bail avec un d&eacute;p&ocirc;t de garantie de $depot &#8364; et une valeur de rachat de $valeur &#8364;, dont les remboursements de $remboursements &#8364; s'effetuent en $debfinPhrase de p&eacute;riode sur une dur&eacute;e de $nbloyers $periodicitePhrase est soumis au taux annuel proportionnel de $resultFormat % soit un taux annuel &eacute;quivalent de <strong>$tresultFormat %</strong>.  </div></div>";		
	
	//Retour positif.
	return array($resultPhrase, $errorMessages);
}

/*---------------------------------------------------------------------------------------------------
 *  cld : Calcul de la durée
 ----------------------------------------------------------------------------------------------------
 */
 
function cld($intMontant, $intDepot, $intRemboursements, $intPeriodicite, $blnDebut, $intValeur, $fltTaux) {

	$txM = 0.0;
	
	$txM = ($fltTaux / $intPeriodicite / 100);
	
	if($blnDebut == TRUE)
	{
		 $Result1=(((-$intRemboursements * (1 + $txM)) / $txM) + $intValeur - $intDepot) / (($intMontant - $intDepot) - ($intRemboursements * (1 + $txM) / $txM));
		 $Result2 = 1 + $txM;
	 }
	 else
	 {
	 	$Result1=((-$intRemboursements / $txM) + $intValeur - $intDepot) / (($intMontant - $intDepot) - ($intRemboursements / $txM));
		 $Result2 = 1 + $txM;
	 }
	
	$result = 0;
	
	if ($Result1 >= 0)
	{
		if ($Result2 >= 0)
		{
		$result = log($Result1) / log($Result2);
		}
	}
	
	return $result;
}

/*---------------------------------------------------------------------------------------------------
 *  cldService : Methode d'interface publique du protocole soap pour le service de cld
 ----------------------------------------------------------------------------------------------------
 */
 
function cldService($montant, $depot, $remboursements, $periodicite, $debut, $valeur, $taux)  {

	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('Montant', $montant, '10000').
			checkInteger('D&eacute;p&ocirc;t', $depot, '10000').
			checkInteger('Remboursements', $remboursements, '10000').
			checkInteger('Valeur', $valeur, '10000').
			checkDecimal('Taux', $taux, '3.2').
			calculLoyersMV($montant,$valeur).
			calculLoyersMD($montant,$depot);
	
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a &eacute;t&eacute; d&eacute;tect&eacute;e.', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.	
	$result = cld($montant, $depot, $remboursements, $periodicite, $debut, $valeur, $taux);

	//Formattage de la phrase de retour du service.
	switch($debut) {
		case TRUE:$debfinPhrase = 'debut'; break;
		case FALSE:$debfinPhrase = 'fin'; break;
		default:$debfinPhrase = 'debut';
	}

	$pluriel = ($result > 1)?'s':'';

	switch($periodicite) {
		case '1':$periodicitePhrase = 'an'.$pluriel;break;
		case '2':$periodicitePhrase = 'semestre'.$pluriel;break;
		case '4':$periodicitePhrase = 'trimestre'.$pluriel;break;
		case '12':$periodicitePhrase = 'mois';break;
		default:$periodicitePhrase = 'mois';
	}

	$resultFormat = number_format($result);

	$resultPhrase = "<div class='exempleResultat'><h4>R&eacute;sultat</h4><div class='res'>Un cr&eacute;dit-bail de $montant &#8364;, avec un d&eacute;p&ocirc;t de garantie de $depot &#8364; et une valeur de rachat de $valeur &#8364;, dont les remboursements constants de $remboursements &#8364; s'effetuent en $debfinPhrase de p&eacute;riode au taux annuel proportionnel de $taux % aura une dur&eacute;e de <strong>$resultFormat $periodicitePhrase</strong>.  </div></div>";	
	
	//Retour positif.
	return array($resultPhrase, $errorMessages);
}

/*---------------------------------------------------------------------------------------------------
 *  srtb : Seuil de rentabilité
 ---------------------------------------------------------------------------------------------------
 */
 
function srtb($intMarge, $intChargevar, $intChargestruct, $intRemuneration, $intCa) {
	
	$x = $intChargestruct + $intRemuneration; //pour1
	$y = $intMarge - $intChargevar;           //pour2
		
		$result1 = $x * (100 / $y);  //seuil
		$result2 = $result1 / 12; //chiffres d'affaires moyen mensuel
		$result3 = $intCa / $result1; //indice de securité
		$result4 = ((($intCa - $result1) / $intCa) * 100 ); //baisse du chiffre d'affaire
		$result5 = $intCa - $result1; //montant
		$result6 = ((($result1 - $intCa) / $intCa) * 100 ) ; //progression du chiffre d'affaires
		
			
	return array($result1, $result2, $result3, $result4, $result5, $result6);

}

/*---------------------------------------------------------------------------------------------------
 *  srtbService : Methode d'interface publique du protocole soap pour le service de srtb
 ---------------------------------------------------------------------------------------------------
 */

function srtbService($marge, $chargevar, $chargestruct, $remuneration, $ca) {

	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('taux de marge brute', $marge, '20').
			checkInteger('charges variables', $chargevar, '5000').
			checkInteger('charges de structures annuelles', $chargestruct, '100').
			checkInteger('remuneration', $remuneration, '2000').
			checkInteger('chiffre affaires', $ca, '3000').
			checkSup('Taux de marge brute', $marge, 'Charges variables', $chargevar);
			
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a été détectée.<hr>', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.
	$result = srtb($marge, $chargevar, $chargestruct, $remuneration, $ca);
	
	//Formattage de la phrase de retour du service.
	$seuil = format_decimal($result[0]);
	$camoyen = format_decimal($result[1]);
	$indsec = format_decimal($result[2]);
	$baisse = format_decimal($result[3]);
	$montant = format_decimal($result[4]);
	$progression = format_decimal($result[5]);
	
	if($result[0] < $ca) {
		$resultPhrase = "<div class='exempleResultat'><h4>R&eacute;sultat</h4><div class='res'>Votre seuil de rentabilité est atteint pour un chiffre d'affaires annuel de $seuil €.<br>Soit un chiffre d'affaires moyen mensuel de $camoyen €.<br>Votre indice de sécurité est de $indsec .<br>Ce qui veut dire que votre chiffre d'affaires peut baisser de $baisse % (ou de $montant € HT) avant que l'exploitant ne dégage de pertes.  </div></div>";
		}				 
	else {
		$resultPhrase = "<div class='exempleResultat'><h4>R&eacute;sultat</h4><div class='res'>Votre seuil de rentabilité est atteint pour un chiffre d'affaires annuel de $seuil €.<br>Soit un chiffre d'affaires moyen mensuel de $camoyen €.<br>Vous réalisez des pertes, pour atteindre votre seuil de rentabilité, votre chiffre d'affaires doit progresser de $progression %. </div></div>";
	}
	//Retour positif.
	return array($resultPhrase, $errorMessages);
	
}
/*---------------------------------------------------------------------------------------------------
 *  fk : frais kilométriques 
 ----------------------------------------------------------------------------------------------------
 */
 
function fk($intPuissance, $intDistance) {
	include('acces_db.php');

	//création de la requête SQL:
	$sql = "SELECT * FROM km Where NB = $intPuissance AND BINF <= $intDistance AND BSUP >= $intDistance";
	$requete = mysql_query( $sql, $cnx ) or die( "ERREUR MYSQL numéro: ".mysql_errno()."<br>Type de cette erreur: ".mysql_error()."<br>\n" );
	
	while( $result = mysql_fetch_array( $requete ) )
	{
		$TAUX = $result["TAUX"];
		$AJOUTE = $result["AJOUTE"];	
	}
	$resultat=(int)(($TAUX * $intDistance) + $AJOUTE);
	
	// Et pour mettre fin à la connexion
	mysql_close($cnx);

	return $resultat;
}

/*---------------------------------------------------------------------------------------------------
 *  fkService : Methode d'interface publique du protocole soap pour le service de fk
 ----------------------------------------------------------------------------------------------------
 */
 
function fkService($puissance, $distance)  {

	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('distance', $distance, '1000');
	
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a &eacute;t&eacute; d&eacute;tect&eacute;e.', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.
	$resultat = fk($puissance, $distance);

	$resultFormat = format_decimal($resultat);

	$resultPhrase = "Montant des frais Kilom&eacute;triques : $resultat euros";
	
	//Retour positif.
	return array($resultPhrase, $errorMessages);
}

/*---------------------------------------------------------------------------------------------------
 *  vt : versement transport
 ----------------------------------------------------------------------------------------------------
 */
 
function vt($intDepartement) {
	include('acces_db.php');
	//création de la requête SQL:

	$db = $GLOBALS['TYPO3_DB'];

	$sql = "SELECT * FROM tauverse Where DEPT = $intDepartement order by VILLE asc";
	$requete = mysql_query( $sql ) or die( "ERREUR MYSQL numéro: ".mysql_errno()."<br>Type de cette erreur: ".mysql_error()."<br>\n" );
	
	$resultPhrase = $resultPhrase . "<table border='1' cellpadding='2' cellspacing='0' width='100%' class='table1'>";
	$resultPhrase = $resultPhrase . "<thead>";
	$resultPhrase = $resultPhrase . "<tr>";
	$resultPhrase = $resultPhrase . "<th id='thTitre1'>Département</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre2'>".$intDepartement."</th>";
	$resultPhrase = $resultPhrase ."<TR>";
	$resultPhrase = $resultPhrase ."<TR>";
	$resultPhrase = $resultPhrase . "<th id='thTitre3'>&nbsp;</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre4'>&nbsp;</th>";
	$resultPhrase = $resultPhrase ."<TR>";	
	$resultPhrase = $resultPhrase ."<TR>";
	$resultPhrase = $resultPhrase . "<th id='thTitre5'>Ville ou groupement</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre6'>Taux</th>";
	$resultPhrase = $resultPhrase . "</tr>";
	$resultPhrase = $resultPhrase . "</thead>";
	$resultPhrase = $resultPhrase . "<tbody>";
	
	while( $result = mysql_fetch_array( $requete ) )
	{		
		$resultPhrase = $resultPhrase ."<TR>";
		$resultPhrase = $resultPhrase ."<td headers='thTitre1'>";	
		$resultPhrase = $resultPhrase .$result["VILLE"];	
		$resultPhrase = $resultPhrase ."</TD>";		
		$resultPhrase = $resultPhrase ."<td headers='thTitre2'>";	
		$resultPhrase = $resultPhrase .$result["TAU"];
		$resultPhrase = $resultPhrase ."</TD>";
		$resultPhrase = $resultPhrase ."</TR>";	
	}	
	$resultPhrase = $resultPhrase . "</tbody>";
	$resultPhrase = $resultPhrase ."</TABLE>";
	
	return $resultPhrase;
}

/*---------------------------------------------------------------------------------------------------
 *  vtService : Methode d'interface publique du protocole soap pour le service de vt
 ----------------------------------------------------------------------------------------------------
 */
 
function vtService($departement)  {

	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('departement', $departement, '1000');
	
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a &eacute;t&eacute; d&eacute;tect&eacute;e.', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.
	$resultPhrase = vt($departement);

	//Retour positif.
	return array($resultPhrase, $errorMessages);
}

/*---------------------------------------------------------------------------------------------------
 *  hs : Heures supplémentaires - Equivalence 
 ----------------------------------------------------------------------------------------------------
 */
 
function hs($intHeures, $intSalaire) {

	$heures = $intHeures;
	$salaire = $intSalaire;
	
	$hebdo=$intHeures*35/151.67;
	if ($intHeures<151.67)
	{
		$sal = $intHeures * $intSalaire / 151.67;
	}
	else
	{
		$hs50 = $hebdo - 43;
		if ($hs50 > 0)
			$hs25 = 8;
		else
			$hs25 = $hebdo - 35;
		if ($hs50<0)
			$hs50 = 0;
		if ($hs25<0)
			$hs25 = 0;
		$sal = $intSalaire + ($intSalaire*1.25*$hs25/35) + ($intSalaire*1.5*$hs50/35);
	}
	return array($hebdo,$sal,$heures,$salaire);
}

/*---------------------------------------------------------------------------------------------------
 *  hsService : Methode d'interface publique du protocole soap pour le service de hs
 ----------------------------------------------------------------------------------------------------
 */
 
function hsService($heures, $salaire)  {

	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('Heures', $heures, '24').
			checkInteger('Salaire', $salaire, '10000');
					
	
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a &eacute;t&eacute; d&eacute;tect&eacute;e.', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.
	list($hebdo,$sal,$heures,$salaire) = hs($heures, $salaire);

	$resultSal = format_decimal($sal);
	$resultHebdo = format_decimal($hebdo);

	$resultPhrase = "<div class='exempleResultat'><h4>R&eacute;sultat</h4><div class='res'>Nombre d'heures effectuées dans le mois : $heures heures<br>Salaire brut mensuel (base 151,67 heures) : $salaire Euros<br><br>Equivalence d'un horaire hebdomadaire constant de $resultHebdo heures<br><br>Le salaire brut mensuel (y compris les heures supplémentaires) est de <strong>$resultSal &#8364;</strong>. </div></div>";
	
	//Retour positif.
	return array($resultPhrase, $errorMessages);
}

/*---------------------------------------------------------------------------------------------------
 *  acs : abscences et cong&eacute;s du salari&eacute; 
 ----------------------------------------------------------------------------------------------------
 */
 
function acs($intnbjours) {

$nbconge=0;
if ($intnbjours>=0 && $intnbjours<=20)
	$nbconge=30;
if ($intnbjours>=21 && $intnbjours<=40)
	$nbconge=28;
if ($intnbjours>=41 && $intnbjours<=43)
	$nbconge=26;
if ($intnbjours>=44 && $intnbjours<=60)
	$nbconge=25;
if ($intnbjours>=61 && $intnbjours<=80)
	$nbconge=23;
if ($intnbjours>=81 && $intnbjours<=86)
	$nbconge=21;
if ($intnbjours>=87 && $intnbjours<=100)
	$nbconge=20;
if ($intnbjours>=101 && $intnbjours<=103)
	$nbconge=19;
if ($intnbjours>=104 && $intnbjours<=120)
	$nbconge=18;
if ($intnbjours>=121 && $intnbjours<=129)
	$nbconge=16;
if ($intnbjours>=130 && $intnbjours<=140)
	$nbconge=15;
if ($intnbjours>=141 && $intnbjours<=147)
	$nbconge=14;
if ($intnbjours>=148 && $intnbjours<=160)
	$nbconge=13;
if ($intnbjours>=161 && $intnbjours<=164)
	$nbconge=12;
if ($intnbjours>=165 && $intnbjours<=173)
	$nbconge=11;
if ($intnbjours>=174 && $intnbjours<=181)
	$nbconge=10;
if ($intnbjours>=182 && $intnbjours<=190)
	$nbconge=9;
if ($intnbjours>=191 && $intnbjours<=200)
	$nbconge=8;
if ($intnbjours>=201 && $intnbjours<=207)
	$nbconge=7;
if ($intnbjours>=208 && $intnbjours<=216)
	$nbconge=6;
if ($intnbjours>=217 && $intnbjours<=225)
	$nbconge=5;
if ($intnbjours>=226 && $intnbjours<=233)
	$nbconge=4;
if ($intnbjours>=234 && $intnbjours<=240)
	$nbconge=3;
if ($intnbjours>=240)
	$nbconge=0;

if ($nbconge>=0 && $nbconge<=5)
	$jvre=$nbconge;
if ($nbconge>=6 && $nbconge<=11)
	$jvre=$nbconge-1;	
if ($nbconge>=12 && $nbconge<=17)
	$jvre=$nbconge-2;	
if ($nbconge>=18 && $nbconge<=23)
	$jvre=$nbconge-3;	
if ($nbconge>=24 && $nbconge<=29)
	$jvre=$nbconge-4;
if ($nbconge==30)
	$jvre=$nbconge-5;

	return array($nbconge,$jvre);
}

/*---------------------------------------------------------------------------------------------------
 *  acsService : Methode d'interface publique du protocole soap pour le service de acs
 ----------------------------------------------------------------------------------------------------
 */
 
function acsService($nbjours)  {

	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('nbjours', $nbjours, '10000');
	
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a &eacute;t&eacute; d&eacute;tect&eacute;e.', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.
	list($nbconge,$jvre) = acs($nbjours);

	$resultPhrase = "<div class='exempleResultat'><h4>R&eacute;sultat</h4><div class='res'>Le nombre de jours de cong&eacute;s de votre salari&eacute; est de :<br>- en jours ouvrables : <strong>$nbconge jours</strong><br>- en jours ouvr&eacute;s :<strong>$jvre jours</strong>. </div></div>";
	
	//Retour positif.
	return array($resultPhrase, $errorMessages);
}

/*---------------------------------------------------------------------------------------------------
 *  cjo : correspondance : jours ouvrés / jours ouvrables 
 ----------------------------------------------------------------------------------------------------
 */
 
function cjo($intnbjours1,$intnbjours2) {
	$result ="";
		if ($intnbjours1 > 0)
			{
			if ($intnbjours1 >= 0 && $intnbjours1 <= 5)
				{
					$intnbjours2 = $intnbjours1;
				}
				if ($intnbjours1 >= 6 && $intnbjours1 <= 11)
					{
						$intnbjours2 = $intnbjours1 - 1;
					}
					if ($intnbjours1 >= 12 && $intnbjours1 <= 17)
						{
							$intnbjours2 = $intnbjours1 - 2;
						}
						if ($intnbjours1 >= 18 && $intnbjours1 <= 23)
							{
								$intnbjours2 = $intnbjours1 - 3;
							}
							if ($intnbjours1 >= 24 && $intnbjours1 <= 29)
								{
									$intnbjours2 = $intnbjours1 - 4;
								}
								if ($intnbjours1 == 30)
									{
										$intnbjours2 = $intnbjours1 - 5;
									}
									$result = $intnbjours2;
									return $result;
				}
			else
				{
				if ($intnbjours2 >= 0 && $intnbjours2 <= 4)
					{
						$intnbjours1 = $intnbjours2;
					}
					if ($intnbjours2 >= 5 && $intnbjours2 <= 9)
						{
							$intnbjours1 = $intnbjours2 - 1 + 2;
						}
						if ($intnbjours2 >= 10 && $intnbjours2 <= 14)
							{
								$intnbjours1 = $intnbjours2 - 1 + 3;
							}
							if ($intnbjours2 >= 15 && $intnbjours2 <= 19)
								{
									$intnbjours1 = $intnbjours2 - 1 + 4;
								}
								if ($intnbjours2 >= 20 && $intnbjours2 <= 24)
									{
										$intnbjours1 = $intnbjours2 - 1 + 5;
									}
									if ($intnbjours2 == 25)
										{
											$intnbjours1 = $intnbjours2 - 1 + 6;
										}
										$result = $intnbjours1;
										return $result;
					}	
}

/*---------------------------------------------------------------------------------------------------
 *  cjoService : Methode d'interface publique du protocole soap pour le service de acs
 ----------------------------------------------------------------------------------------------------
 */
 
function cjoService($nbjours1,$nbjours2)  {

	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('nbjours2', $nbjours2, '25').
			checkInteger('nbjours1', $nbjours1, '28').
			controljours($nbjours1,$nbjours2);
	
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a &eacute;t&eacute; d&eacute;tect&eacute;e.', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.
	$result = cjo($nbjours1,$nbjours2);
	
	if ($nbjours1>0)
	$resultPhrase = $result ." jours ouvrés";
	else
	$resultPhrase = $result ." jours ouvrables";
	
	//Retour positif.
	return array($resultPhrase, $errorMessages);
}

/*---------------------------------------------------------------------------------------------------
 *  va : vignette automobile 
 ----------------------------------------------------------------------------------------------------
 */

function datediff($per,$d1,$d2) {
   $d = $d2-$d1;
   switch($per) {
      case "yyyy": $d/=12;
      case "m": $d*=12*7/365.25;
      case "ww": $d/=7;
      case "d": $d/=24;
      case "h": $d/=60;
      case "n": $d/=60;
   }
   return round($d);
}

function va($intdepartement,$intpuissance,$intjour,$intmois,$intannee,$intgenre,$intfonctionnement) {
	$result ="";
	$nbjourecoule=0;
		
if ($intpuissance<5)
	$xcat=1;
else
	{
	if ($intpuissance<8)
		$xcat=2;
	else
		{
		if ($intpuissance<10)
			$xcat=3;
		else
			{
			if ($intpuissance<12)
				$xcat=4;
			else
				{
				if (($intpuissance<15 && $intgenre=1) || ($intpuissance<17 && $intgenre=2))
					$xcat=5;
				else
					{
					if ($intpuissance<17 && $intgenre=1)
						$xcat=6;
					else
						{
						if (($intpuissance<18 && $intgenre=1) || ($intpuissance>16 && $intgenre=2))
							$xcat=7;
						else
							{
							if ($intpuissance<21 )
								$xcat=8;
							else
								{
								if ($intpuissance<23) 
									$xcat=9;
								else
									{
									if ($intpuissance>22)
										$xcat=10;
									}
								}
							}
						}
					}
				}
			}
		}
	}

$Aujourdhui = getdate();
$datelim = strtotime("01/12/".$Aujourdhui['year']);
$datecirc = strtotime($intjour."/".$intmois."/".$intannee);
$nbjourecoule=datediff("d", $datecirc, $datelim); 
//$nbjourecoule=($nbjourecoule-1);

if ($nbjourecoule < 1826)
	{
	$xvig="A";
	}
else
	{
	if ($nbjourecoule < 7305) 
		{
		$xvig="H";
		}
	else
		{
		if($nbjourecoule < 9131) 
			{
			$xvig="S";
			}
		else
			{
			$xvig="O";
			}
		}
	}

	
if ($xvig=="A")
	$ncat=$xcat;
else
	{
	if ($xvig=="H")
		$ncat=$xcat+10;
	else
		{
		if ($xvig=="S")
			$ncat=21;
		}
	}

if ($intdepartement>95 )
	$dep = ($intdepartement-875);
else
{
	$dep = $intdepartement;
}

$nb = (($dep-1)*21+$ncat);
	include('acces_db.php');

	if ($intfonctionnement==1)
			$sql = "SELECT PRIX_VIG from VIGNETTE";
	else
	{
			$sql = "SELECT PRIX_VIG from VIGNESPE";
    }
    
    $requete = mysql_query( $sql ) or die( "ERREUR MYSQL numéro: ".mysql_errno()."<br>Type de cette erreur: ".mysql_error()."<br>\n" );
	
	$nbboucle=0;
	if ($xvig=="O" )
	{
		$resultat = 0;
	}
	else
		 {
		 while ($nbboucle < $nb)
			{
			$result = mysql_fetch_object( $requete ); 
			$resultat = $result->PRIX_VIG;
			$nbboucle=($nbboucle+1);			
			}
		}
	// Et pour mettre fin à la connexion
	return array($resultat,$xvig,$xcat);
}

/*---------------------------------------------------------------------------------------------------
 *  vaService : Methode d'interface publique du protocole soap pour le service de va
 ----------------------------------------------------------------------------------------------------
 */
 
function vaService($departement,$puissance,$jour,$mois,$annee,$genre,$fonctionnement)  {

	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('departement', $departement, '25').
			checkInteger('puissance', $puissance, '28').
			checkInteger('jour', $jour, '25').
			checkInteger('mois', $mois, '10').
			checkInteger('annee', $annee, '2000');
	
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a &eacute;t&eacute; d&eacute;tect&eacute;e.', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.
	List($resultat,$xvig,$xcat) = va($departement,$puissance,$jour,$mois,$annee,$genre,$fonctionnement);
	
	if ($xvig=="O")
	{
		$type="Exonéré";
	}
	else
	{
		$type=($xvig.$xcat);
	}

	$resultPhrase = "<div class='exempleResultat'><h4>R&eacute;sultat</h4><div class='res'>Type de votre vignette = $type <br>Prix de votre vignette = $resultat euros. </div></div>";
		
	//Retour positif.
	return array($resultPhrase, $errorMessages);
}

/*---------------------------------------------------------------------------------------------------
 *  jal : journaux d'annonces légales
 ----------------------------------------------------------------------------------------------------
 */
 
function jal($intDepartement) {
	include('acces_db.php');

	//création de la requête SQL:
	$sql = "SELECT annonceur.id_annonc, annonceur.nom_annonc, dept_ann.id_dept FROM annonceur INNER JOIN dept_ann ON annonceur.id_annonc = dept_ann.id_annonc WHERE dept_ann.id_dept= $intDepartement GROUP BY annonceur.id_annonc, annonceur.nom_annonc, dept_ann.id_dept ORDER BY annonceur.nom_annonc, dept_ann.id_dept";
	$requete = mysql_query( $sql ) or die( "ERREUR MYSQL numéro: ".mysql_errno()."<br>Type de cette erreur: ".mysql_error()."<br>\n" );
	
	$resultPhrase = $resultPhrase . "<table border='1' cellpadding='2' cellspacing='0' width='100%' class='table1'>";
	$resultPhrase = $resultPhrase . "<thead>";
	$resultPhrase = $resultPhrase . "<tr>";
	$resultPhrase = $resultPhrase . "<th id='thTitre1'>No</th>";
	$resultPhrase = $resultPhrase . "<th id='thTitre1'>Journaux d'annonces légales</th>";
	$resultPhrase = $resultPhrase . "</tr>";
	$resultPhrase = $resultPhrase . "</thead>";
	$resultPhrase = $resultPhrase . "<tbody>";
	
	$i=1;
	while( $result = mysql_fetch_array( $requete ) )
	{		
		$resultPhrase = $resultPhrase ."<TR>";
		$resultPhrase = $resultPhrase ."<td headers='thTitre1'>";	
		$resultPhrase = $resultPhrase .$i;	
		$resultPhrase = $resultPhrase ."</TD>";		
		$resultPhrase = $resultPhrase ."<td headers='thTitre2'>";	
		$resultPhrase = $resultPhrase .$result["nom_annonc"];
		$resultPhrase = $resultPhrase ."</TD>";
		$resultPhrase = $resultPhrase ."</TR>";	
		$i=$i+1;
	}	
	$resultPhrase = $resultPhrase . "</tbody>";
	$resultPhrase = $resultPhrase ."</TABLE>";	
	
	return $resultPhrase;
}

/*---------------------------------------------------------------------------------------------------
 *  jalService : Methode d'interface publique du protocole soap pour le service de jal
 ----------------------------------------------------------------------------------------------------
 */
 
function jalService($departement)  {

	// Validation des paramètres passés.
	$errorMessages = 
			checkInteger('departement', $departement, '1000');
	
	// Retour négatif en cas de défaut de validation.
	if($errorMessages != '') {
		return array('Une erreur a &eacute;t&eacute; d&eacute;tect&eacute;e.', $errorMessages);
	}
	
	//Appel de la fonction de calcul du résultat.
	$resultPhrase = jal($departement);

	//Retour positif.
	return array($resultPhrase, $errorMessages);
}


?>