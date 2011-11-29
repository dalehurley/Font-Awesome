<?php
/*
 * Utilisation du service web clService
 * G.R. - expeo - 04/09/06
 * grg@expeolabs.com
 * 
 * La partie client est en charge de la pr?sentation, de la r?cup?ration des param?tres et de l'invocation du service.
 * La partie  serveur est en charge de la validation des param?tres pass?s et de la fourniture d'un r?sultat au format suivant: array($strResult, $strErrorMessage)
 */
 
//requis par nusoap
set_magic_quotes_runtime(0);
require_once('nusoap-0.7.2/lib/nusoap.php');
require_once('configuration.php');

//Initialtion des cha?nes de messages utilisateur.
$errorMessages = "";		
$resultPhrase =  "";

//Extraction des variables post.
extract($_POST);

// Appel du service uniquement lorsque le formulaire a ?t? soumis.
if (!isSet($_POST['calculer'])) {
	
	//Initialisation des variables de formulaire
	$montant = "";
	$depot = "";
	$nbloyers = "";
	$periodicite = 1;
	$debut = 1;
	$valeur = "";
	$taux = "";
}
else {

	//D?finition des param?tres d'appel du service depuis les variables post.
	$parameters = array(
		"montant" => $montant,
		"depot" => $depot,
		"nbloyers" => $nbloyers,
		"periodicite" => $periodicite,
		"debut" => $debut,
		"valeur" => $valeur,
		"taux" => $taux
	);
	
	//Invocation standard du service web : renvoit le tableau suivant : array($strResult, $errorMessages)
	$soapclient = new soapclient($SOAP_SERVER_URL);
	$result = $soapclient->call('clService', $parameters);		
	$resultPhrase =  $result[0];
	$errorMessages = $result[1];
}
// Lien vers la page mère PID
$lienPid = $GLOBALS["TSFE"]->cObj->getTypolink_URL($GLOBALS["TSFE"]->page["pid"]);
?>
<div id="centreContenu">
<div id="simulateursCalculs">
	<div id="divTitrePageActuelle"><h1><a href="<?echo $lienPid;?>">Simulateurs - <span>Calculs financiers - Crédit-bail</span></a></h1></div>
	<div id="itemlisteSimulateurs">
		<div class="itemlisteSimulateurs">
			<form action="" id="f" method="post">
				<div>
					<h4>Calcul des loyers</h4><span></span><br />
					<div id="error"><?=$errorMessages?></div>
					<div><label for="montant">Montant financ&eacute;</label><input id="montant" name="montant" size="11" type="text" value="<?=$montant?>" /></div><div style="clear:both;"></div>
					<div><label for="depot">D&eacute;p&ocirc;t initial</label><input id="depot" name="depot" size="11" type="text" value="<?=$depot?>" /></div><div style="clear:both;"></div>
					<div><label for="nbloyers">Nombre de loyers</label><input id="nbloyers" name="nbloyers" size="5" type="text" value="<?=$nbloyers?>" /></div><div style="clear:both;"></div>
					<div><label for="periodicite">Périodicité </label><select id="periodicite" name="periodicite">
						<option value="12" <?=($periodicite == '12')?"selected=\"selected\"":""?>>Mois</option>
						<option value="1" <?=($periodicite ==  '1')?"selected=\"selected\"":""?>>Année</option>
						<option value="4" <?=($periodicite ==  '4')?"selected=\"selected\"":""?>>Trimestre</option>
						<option value="2" <?=($periodicite ==  '2')?"selected=\"selected\"":""?>>Semestre</option>
					</select></div><div style="clear:both;"></div>
					<fieldset>
						<legend>Versement en</legend>
						<label for="debut"><input type="radio" name="debut" id="debut" value="1" <?=($debut==true)?"checked=\"checked\"":""?> /> Début de période</label><label for="fin"><input type="radio" name="debut" id="fin" value="0" <?=($debut==false)?"checked=\"checked\"":""?> /> Fin de période</label>
					</fieldset><div style="clear:both;"></div>				
					<div><label for="valeur">Valeur de rachat </label><input name="valeur" id="valeur" size="11" type="text" value="<?=$valeur?>" /></div><div style="clear:both;"></div>
					<div><label for="taux">Taux proportionnel annuel </label><input name="taux" id="taux" size="5" type="text" value="<?=$taux?>" 
					/></div><div style="clear:both;"></div>
					<div style="clear:both;"></div>
					<div class="divBoutons"><button type="submit" name="calculer">Calculer</button>&nbsp;&nbsp;<button type="reset" name="effacer">Effacer</button></div>
					<div style="clear:both;"></div>
				</div>
			</form>
			<p><?=$resultPhrase?></p>
			<br />
			<div style="clear:both;"></div>
		</div>
		<div style="clear:both;"></div>
	</div>
</div>
</div>
