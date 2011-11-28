<?
/*
 * Utilisation du service web vacpicddService
 * G.R. - expeo - 04/09/06
 * grg@expeolabs.com
 * 
 * La partie client est en charge de la présentation, de la récupération des paramètres et de l'invocation du service.
 * La partie  serveur est en charge de la validation des paramètres passés et de la fourniture d'un résultat au format suivant: array($strResult, $strErrorMessage)
 */
 
//requis par nusoap
set_magic_quotes_runtime(0);
require_once('nusoap-0.7.2/lib/nusoap.php');
require_once('configuration.php');

//Initialtion des chaînes de messages utilisateur.
$errorMessages = "";		
$resultPhrase =  "";

//Extraction des variables post.
extract($_POST);

// Appel du service uniquement lorsque le formulaire a été soumis.
if (!isSet($_POST['calculer'])) {
	
	//Initialisation des variables de formulaire
	$capital = "";
	$periodicite = 1;
	$duree = "";
	$debut = 1;
	$taux = "";
}
else {

	//Définition des paramètres d'appel du service depuis les variables post.
	$parameters = array(
		"capital" => $_POST['capital'],
		"periodicite" => $_POST['periodicite'],
		"duree" => $_POST['duree'],
		"debut" => $_POST['debut'],
		"taux" => $_POST['taux']
	);
	
	//Invocation standard du service web : renvoit le tableau suivant : array($strResult, $errorMessages)
	$soapclient = new soapclient($SOAP_SERVER_URL);
	
	$result = $soapclient->call('vacpicddService', $parameters);

	$resultPhrase =  $result[0];
	$errorMessages = "<br />".$result[1];
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
					<h4>Valeur acquise par un capital placé à intérêts composés pendant une durée déterminée</h4><span></span><br />
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
				<div class="res">Vous voulez savoir quel sera le montant de votre capital, actuellement de 34&nbsp;000&nbsp;&euro; si vous le placez sur un compte bloqué pendant 5 ans au taux de 9&nbsp;%.</div>
				<div style="padding: 0 5px;">
					<div><span>Montant du capital placé&nbsp;</span><span class="valeur">34&nbsp;000</span></div><div style="clear:both;"></div>
					<div><span>Durée du placement (en nombre de périodes)&nbsp;</span><span class="valeur">5</span></div><div style="clear:both;"></div>
					<div><span>Périodicité (M&nbsp;:&nbsp;mois, A&nbsp;:&nbsp;ans, T&nbsp;:&nbsp;trimestres; S&nbsp;:&nbsp;semestres)&nbsp;</span><span class="valeur">A</span></div><div style="clear:both;"></div>
					<div><span>Versement Fin ou Début de période (F ou D)&nbsp;</span><span class="valeur">F</span></div><div style="clear:both;"></div>
					<div><span>Taux proportionnel annuel&nbsp;</span><span class="valeur">9</span></div><div style="clear:both;"></div>
					<div style="clear:both;"></div>
				</div>
			</div>
			<div class='exempleResultat'>
				<h4>Résultat</h4>
				<div class="res">Un capital de 34&nbsp;000 &euro; placé en fin de période pendant 5 ans 
				au taux proportionnel annuel de 9% s'élève à : <br /><strong>52&nbsp;313,21&nbsp;&euro;</strong>.</div>
			</div>
			<div style="clear:both;"></div>
		</div>
		<div style="clear:both;"></div>
	</div>
</div>
</div>