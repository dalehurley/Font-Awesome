<?php
/*
 * Utilisation du service web trcService
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
	$capital = "";
	$periodicite = 1;
	$duree = "";
	$debut = 1;
	$capitalacquis = "";
}
else {

	//D?finition des param?tres d'appel du service depuis les variables post.
	$parameters = array(
		"capital" => $capital,
		"periodicite" => $periodicite,
		"duree" => $duree,
		"debut" => $debut,
		"capitalacquis" => $capitalacquis
	);
	
	//Invocation standard du service web : renvoit le tableau suivant : array($strResult, $errorMessages)
	$soapclient = new soapclient($SOAP_SERVER_URL);
	$result = $soapclient->call('trcService', $parameters);		
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
					<h4>Taux de rendement d'un capital</h4><span></span><br />
					<div id="error"><?=$errorMessages?></div>
					<div><label for="capital">Montant du capital placé </label><input id="capital" name="capital" size="11" type="text" value="<?=$capital?>" /></div><div style="clear:both;"></div>
					<div><label for="duree">Durée du placement (en nombre de périodes) </label><input id="duree" name="duree" size="5" type="text" value="<?=$duree?>" /></div><div style="clear:both;"></div>
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
					<div><label for="capitalacquis">Capital acquis </label><input name="capitalacquis" id="capitalacquis" size="11" type="text" value="<?=$capitalacquis?>" /></div><div style="clear:both;"></div>
					<div style="clear:both;"></div>
					<div class="divBoutons"><button type="submit" name="calculer">Calculer</button>&nbsp;&nbsp;<button type="reset" name="effacer">Effacer</button></div>
					<div style="clear:both;"></div>
				</div>
			</form>
			<p><?=$resultPhrase?></p>
			<br />
			<div class='exempleResultat'>
				<h4>Exemple</h4>
				<div class="res">Vous avez plac&eacute; un capital de 40 000 &#8364; pendant 5 ans et aujourd'hui, il s'&eacute;l&egrave;ve &agrave; 55&nbsp;000&nbsp;&#8364;. Quel a &eacute;t&eacute; votre taux de rendement ?</div>
				<div style="padding: 0 5px;">
					<div><span>Montant du capital placé&nbsp;</span><span class="valeur">40&nbsp;000</span></div><div style="clear:both;"></div>
					<div><span>Durée du placement (en nombre de périodes)&nbsp;</span><span class="valeur">5</span></div><div style="clear:both;"></div>
					<div><span>Périodicité (M&nbsp;:&nbsp;mois, A&nbsp;:&nbsp;ans, T&nbsp;:&nbsp;trimestres; S&nbsp;:&nbsp;semestres)&nbsp;</span><span class="valeur">A</span></div><div style="clear:both;"></div>
					<div><span>Versement Fin ou Début de période (F ou D)&nbsp;</span><span class="valeur">F</span></div><div style="clear:both;"></div>
					<div><span>Capital acquis&nbsp;</span><span class="valeur">55&nbsp;000</span></div><div style="clear:both;"></div>
					<div style="clear:both;"></div>
				</div>
			</div>
			<div class='exempleResultat'>
				<h4>Résultat</h4>
				<div class="res">Un capital de 40&nbsp;000&nbsp;&#8364; plac&eacute; en fin de p&eacute;riode pendant 5 ans<br>
				s'&eacute;l&egrave;ve &agrave; 55&nbsp;000&nbsp;&#8364; si :<br>
				Le taux du placement annuel proportionnel est de&nbsp;:<br /><strong>6.58 %</strong><br>
				soit un taux annuel &eacute;quivalent de&nbsp;:<br /><strong>6.58 %</strong>.
				</div>
			</div>
			<div style="clear:both;"></div>
		</div>
		<div style="clear:both;"></div>
	</div>
</div>
</div>