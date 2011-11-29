<?php
/*
 * Utilisation du service web hsService
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

//Initialtion des chaînes de messages utilisateur.
$errorMessages = "";		
$resultPhrase =  "";
$errorMessages2 = "";		
$resultPhrase2 =  "";

//Extraction des variables post.
extract($_POST);

// Appel du service uniquement lorsque le formulaire a ?t? soumis.
if (!isSet($_POST['calculer'])) {
	
	//Initialisation des variables de formulaire
	$heures = "";
	$salaire = "";
}
else {

	//D?finition des param?tres d'appel du service depuis les variables post.
	$parameters = array(
		"heures" => $heures,
		"salaire" => $salaire
	);

//Invocation standard du service web : renvoit le tableau suivant : array($strResult, $errorMessages)
	$soapclient = new soapclient($SOAP_SERVER_URL);
	$result = $soapclient->call('hsService', $parameters);		
	$resultPhrase =  $result[0];
	$errorMessages = $result[1];
}

  // Appel du service uniquement lorsque le formulaire a ?t? soumis.
if (!isSet($_POST['calculer2'])) {
	
	//Initialisation des variables de formulaire
  $heures = "";
	$salaire = "";
}
else {

	//D?finition des param?tres d'appel du service depuis les variables post.
	$parameters = array(
		"heures" => $heures2,
		"salaire" => $salaire2
	);
	
	//Invocation standard du service web : renvoit le tableau suivant : array($strResult, $errorMessages)
	$soapclient = new soapclient($SOAP_SERVER_URL);
	$result = $soapclient->call('hsService', $parameters);		
	$resultPhrase2 =  $result[0];
	$errorMessages2 = $result[1];
}
?>
<div id="centreContenu">
<div id="simulateursCalculs">
	<div id="divTitrePageActuelle"><h1>Simulateurs - <span>Heures suppl&eacute;mentaires - Equivalence</span></h1></div>
	<div id="itemlisteSimulateurs">
		<div class="itemlisteSimulateurs">
		<div class='exempleResultat'>
				<h4>Entreprises de plus de 20 salari&eacute;s</h4>
				<div class="res"> R&eacute;gime l&eacute;gal des heures suppl&eacute;mentaires : majoration de 25 % pour les 8 premi&egrave;res heures (de la 36<sup>&egrave;me</sup> heure &agrave; la 43<sup>&egrave;me</sup> heure incluse) et de 50 % au-del&agrave; (soit &agrave; partir de la 44<sup>&egrave;me</sup> heure).
				  </div>	
		</div>	
			<form action="" id="f" method="post">
				<div>
					<h4>Saisissez vos donn&eacute;es</h4><span></span><br />
					<div id="error"><?=$errorMessages?></div>
					<div><label for="heures">Nombre d'heures effectu&eacute;es dans le mois</label><input id="heures" name="heures" size="11" type="text" value="<?=$heures?>" /> heures</div><div style="clear:both;"></div>
					<div><label for="salaire">Salaire brut mensuel (base 151,67 heures)</label><input id="salaire" name="salaire" size="11" type="text" value="<?=$salaire?>" /> euros</div><div style="clear:both;"></div><div style="clear:both;"></div>
					<div class="divBoutons"><button type="submit" name="calculer">Calculer</button>&nbsp;&nbsp;<button type="reset" name="effacer">Effacer</button></div>
					<div style="clear:both;"></div>
				</div>
			</form>
			<p><?=$resultPhrase?></p>
			<br />
			
			<div style="clear:both;"></div>
			<div class='exempleResultat'>
			<h4>Entreprises de 20 salari&eacute;s et moins</h4>
				<div class="res"> R&eacute;gime l&eacute;gal des heures suppl&eacute;mentaires : majoration de 10 % de la 36<sup>&egrave;me</sup> heure &agrave; la 39<sup>&egrave;me</sup> heure incluse et de 25 % de la 40<sup>&egrave;me</sup> heure &agrave; la 43<sup>&egrave;me</sup> heure et de 50 % au-del&agrave; (&agrave; partir de la 44<sup>&egrave;me</sup> heure).</div>
			</div>	

			<form action="" id="ff" method="post">
				<div>
					<h4>Saisissez vos donn&eacute;es</h4><span></span><br />
					<div id="error"><?=$errorMessages?></div>
					<div><label for="heures2">Nombre d'heures effectu&eacute;es dans le mois</label><input id="heures2" name="heures2" size="11" type="text" value="<?=$heures2?>" /> heures</div><div style="clear:both;"></div>
					<div><label for="salaire2">Salaire brut mensuel (base 151,67 heures)</label><input id="salaire2" name="salaire2" size="11" type="text" value="<?=$salaire2?>" /> euros</div><div style="clear:both;"></div><div style="clear:both;"></div>
					<div class="divBoutons"><button type="submit" name="calculer2">Calculer</button>&nbsp;&nbsp;<button type="reset" name="effacer2">Effacer</button></div>
					<div style="clear:both;"></div>
				</div>
			</form>
			<p><?=$resultPhrase2?></p>
			<br />
			
			<div style="clear:both;"></div>
		</div>
		<div style="clear:both;"></div>
	</div>
</div>
</div>

