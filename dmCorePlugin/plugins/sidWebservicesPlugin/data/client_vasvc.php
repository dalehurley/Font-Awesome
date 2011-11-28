<?php
/*
 * Utilisation du service web vacpicddService
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
	$versements = "";
	$periodicite = 1;
	$nbversements = "";
	$debut = 1;
	$taux = "";
}
else {

	//D?finition des param?tres d'appel du service depuis les variables post.
	$parameters = array(
		"versements" => $versements,
		"nbversements" => $nbversements,
		"periodicite" => $periodicite,
		"debut" => $debut,
		"taux" => $taux
	);
	
	//Invocation standard du service web : renvoit le tableau suivant : array($strResult, $errorMessages)
	$soapclient = new soapclient($SOAP_SERVER_URL);
	$result = $soapclient->call('vasvcService', $parameters);		
	$resultPhrase =  $result[0];
	$errorMessages = $result[1];
}

// Lien vers la page mère PID
$lienPid = $GLOBALS["TSFE"]->cObj->getTypolink_URL($GLOBALS["TSFE"]->page["pid"]);
?>
<div id="centreContenu">
<div id="simulateursCalculs">
	<div id="divTitrePageActuelle"><h1><a href="<?echo $lienPid;?>">Simulateurs - <span>Calculs financiers - Placements</span></a></h1></div>
	<div id="itemlisteSimulateurs">
		<div class="itemlisteSimulateurs">
			<form action="" id="f" method="post">
				<div>
					<h4>Valeur acquise par une suite de versements constants</h4><span></span><br />
					<div id="error"><?=$errorMessages?></div>
					<div><label for="versements">Montant des versements </label><input id="versements" name="versements" size="11" type="text" value="<?=$versements?>" /></div><div style="clear:both;"></div>
					<div><label for="nbversements">Nombre de versements </label><input id="nbversements" name="nbversements" size="5" type="text" value="<?=$nbversements?>" /></div><div style="clear:both;"></div>
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
					<div><label for="taux">Taux proportionnel annuel </label><input name="taux" id="taux" size="5" type="text" value="<?=$taux?>" /></div><div style="clear:both;"></div>
					<div style="clear:both;"></div>
					<div class="divBoutons"><button type="submit" name="calculer">Calculer</button>&nbsp;&nbsp;<button type="reset" name="effacer">Effacer</button></div>
					<div style="clear:both;"></div>
				</div>
			</form>
			<p><?=$resultPhrase?></p>
			<br />
			<div class='exempleResultat'>
				<h4>Exemple</h4>
				<div class="res">Vous pouvez &eacute;pargner 700 &#8364; par trimestre que vous voulez placer au taux de 10&nbsp;%. Quel sera votre capital dans 6 ans ?</div>
				<div style="padding: 0 5px;">
					<div><span>Montant des versements&nbsp;</span><span class="valeur">700</span></div><div style="clear:both;"></div>
					<div><span>Nombre de versements&nbsp;</span><span class="valeur">24</span></div><div style="clear:both;"></div>
					<div><span>Périodicité (M&nbsp;:&nbsp;mois, A&nbsp;:&nbsp;ans, T&nbsp;:&nbsp;trimestres; S&nbsp;:&nbsp;semestres)&nbsp;</span><span class="valeur">T</span></div><div style="clear:both;"></div>
					<div><span>Versement Fin ou Début de période (F ou D)&nbsp;</span><span class="valeur">F</span></div><div style="clear:both;"></div>
					<div><span>Taux proportionnel annuel&nbsp;</span><span class="valeur">10</span></div><div style="clear:both;"></div>
					<div style="clear:both;"></div>
				</div>
			</div>
			<div class='exempleResultat'>
				<h4>Résultat</h4>
				<div class="res">Le capital acquis au terme de 24 versements trimestriels de 700 &#8364; plac&eacute;s en fin de p&eacute;riode au taux proportionnel annuel de 10 % est de&nbsp;:<br /><strong>22&nbsp;644,33&nbsp;&euro;</strong>.</div>
			</div>
			<div style="clear:both;"></div>
		</div>
		<div style="clear:both;"></div>
	</div>
</div>
</div>