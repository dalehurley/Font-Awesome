<?php
/*
 * Utilisation du service web cceService
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
	$remboursements = "";
	$periodicite = 1;
	$nbremboursements = "";
	$debut = 1;
	$taux = "";
}
else {

	//D?finition des param?tres d'appel du service depuis les variables post.
	$parameters = array(
		"remboursements" => $remboursements,
		"nbremboursements" => $nbremboursements,
		"periodicite" => $periodicite,
		"debut" => $debut,
		"taux" => $taux
	);
	
	//Invocation standard du service web : renvoit le tableau suivant : array($strResult, $errorMessages)
	$soapclient = new soapclient($SOAP_SERVER_URL);
	$result = $soapclient->call('cceService', $parameters);		
	$resultPhrase =  $result[0];
	$errorMessages = $result[1];
}
// Lien vers la page mère PID
$lienPid = $GLOBALS["TSFE"]->cObj->getTypolink_URL($GLOBALS["TSFE"]->page["pid"]);
?>

<div id="centreContenu">
<div id="simulateursCalculs">
	<div id="divTitrePageActuelle"><h1><a href="<?echo $lienPid;?>">Simulateurs - <span>Calculs financiers - Emprunts</span></a></h1></div>
	<div id="itemlisteSimulateurs">
		<div class="itemlisteSimulateurs">
			<form action="" id="f" method="post">
				<div>
					<h4>Calcul du capital emprunt&eacute;</h4><span></span><br />
					<div id="error"><?=$errorMessages?></div>
					<div><label for="remboursements">Montant des remboursements </label><input id="remboursements" name="remboursements" size="11" type="text" value="<?=$remboursements?>" /></div><div style="clear:both;"></div>
					<div><label for="nbremboursements">Nombre de remboursements </label><input id="nbremboursements" name="nbremboursements" size="5" type="text" value="<?=$nbremboursements?>" /></div><div style="clear:both;"></div>
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
				<div class="res">Vous voulez conna&icirc;tre le capital que vous pouvez emprunter, sachant que vous pouvez rembourser 2&nbsp;250&nbsp;&#8364; par semestre sur 10 ans et que le taux propos&eacute; par l'organisme financier avoisinera 10&nbsp;%.</div>
				<div style="padding: 0 5px;">
					<div><span>Montant des remboursements&nbsp;</span><span class="valeur">2&nbsp;250</span></div><div style="clear:both;"></div>
					<div><span>Nombre de remboursements&nbsp;</span><span class="valeur">20</span></div><div style="clear:both;"></div>
					<div><span>Périodicité (M&nbsp;:&nbsp;mois, A&nbsp;:&nbsp;ans, T&nbsp;:&nbsp;trimestres; S&nbsp;:&nbsp;semestres)&nbsp;</span><span class="valeur">S</span></div><div style="clear:both;"></div>
					<div><span>Versement Fin ou Début de période (F ou D)&nbsp;</span><span class="valeur">F</span></div><div style="clear:both;"></div>
					<div><span>Taux proportionnel annuel&nbsp;</span><span class="valeur">10</span></div><div style="clear:both;"></div>
					<div style="clear:both;"></div>
				</div>
			</div>
			<div class='exempleResultat'>
				<h4>Résultat</h4>
				<div class="res">Le capital d'un emprunt rembours&eacute; sur 20 semestres au taux proportionnel annuel (frais inclus) de 10 % avec des versements constants de 2&nbsp;250&nbsp;&#8364; s'effectuant en fin de p&eacute;riode est de :<br /><strong>28&nbsp;039.97&nbsp;&euro;</strong>.</div>
			</div>
			<div style="clear:both;"></div>
		</div>
		<div style="clear:both;"></div>
	</div>
</div>
</div>