<?php //bibliotheque
/*
 * Utilisation du service web acsService
 * G.R. - expeo - 04/09/06
 * grg@expeolabs.com
 * 
 * La partie client est en charge de la pr&eacute;sentation, de la r&eacute;cup&eacute;ration des param&egrave;tres et de l'invocation du service.
 * La partie  serveur est en charge de la validation des param&egrave;tres pass&eacute;s et de la fourniture d'un r&eacute;sultat au format suivant: array($strResult, $strErrorMessage)
 */
 
//requis par nusoap
set_magic_quotes_runtime(0);
require_once('nusoap-0.7.2/lib/nusoap.php');
require_once('configuration.php');
 
//Initialtion des cha&icirc;nes de messages utilisateur.
$errorMessages = "";		
$resultPhrase =  "";

//Extraction des variables post.
extract($_POST);

// Appel du service uniquement lorsque le formulaire a &eacute;t&eacute; soumis.
if (!isSet($_POST['calculer'])) {
	
	//Initialisation des variables de formulaire
	$nbjours = "";	
	}
else {
	//D&eacute;finition des param&egrave;tres d'appel du service depuis les variables post.
	$parameters = array(
		"nbjours" => $nbjours
	);
	
	//Invocation standard du service web : renvoit le tableau suivant : array($strResult, $errorMessages)
	$soapclient = new soapclient($SOAP_SERVER_URL);
	$result = $soapclient->call('acsService', $parameters);		
	$resultPhrase =  $result[0];
	$errorMessages = $result[1];
}
// Lien vers la page mère PID
$lienPid = $GLOBALS["TSFE"]->cObj->getTypolink_URL($GLOBALS["TSFE"]->page["pid"]);
?>

<div id="centreContenu">
<div id="simulateursCalculs">
	<div id="divTitrePageActuelle"><h1><a href="<?echo $lienPid;?>">Simulateurs - <span>Congés payés - Absences et cong&eacute;s du salari&eacute;</span></a></h1></div>
	<div id="itemlisteSimulateurs">
		<div class="itemlisteSimulateurs">
		<div class='exempleResultat'>
				<h4>D&eacute;terminez rapidement les cong&eacute;s de votre salari&eacute;<br/>(Cas g&eacute;n&eacute;ral : &agrave; pr&eacute;ciser avec votre Convention Collective)</h4>
				<div class="res">Il est possible, pour la p&eacute;riode de r&eacute;f&eacute;rence allant du 1er juin au 31 mai, de traduire en chiffres les principes pos&eacute;s par le code du travail pour une dur&eacute;e hebdomadaire de travail r&eacute;partie sur 5 jours (du lundi au vendredi).
				<br/><br/>
				  Afin de d&eacute;terminer rapidement le nombre de jours ouvrables du cong&eacute; de votre salari&eacute;, veuillez indiquer le nombre de jours d'absence de celui-ci.
				  </div>	
		</div>	
			<form action="" id="f" method="post">
				<div>
					<h4>Saisissez vos donn&eacute;es</h4><span></span><br />
					<div id="error"><?=$errorMessages?></div>
					<div><label for="nbjours">Entrez le nombre de jours d'absence du salari&eacute;</label><input id="nbjours" name="nbjours" size="11" type="text" value="<?=$nbjours?>" /> jour(s)</div><div style="clear:both;"></div>
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
