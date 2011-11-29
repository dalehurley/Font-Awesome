<?php //bibliotheque
/*
 * Utilisation du service web cjoService
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
	$nbjours1 = "0";
  $nbjours2 = "0";	
	}
else {
	//D&eacute;finition des param&egrave;tres d'appel du service depuis les variables post.
	
  if ($nbjours1=="")
  {
    $parameters = array(
    "nbjours1" => "0",
		"nbjours2" => $nbjours2
    );
  }
  else
  {
    $parameters = array(
    "nbjours1" => $nbjours1,
		"nbjours2" => "0"
    );
  }
	
	
	//Invocation standard du service web : renvoit le tableau suivant : array($strResult, $errorMessages)
	$soapclient = new soapclient($SOAP_SERVER_URL);
	$result = $soapclient->call('cjoService', $parameters);		
	$resultPhrase =  $result[0];
	$errorMessages = $result[1];
}
// Lien vers la page mère PID
$lienPid = $GLOBALS["TSFE"]->cObj->getTypolink_URL($GLOBALS["TSFE"]->page["pid"]);
?>
<div id="centreContenu">
<div id="simulateursCalculs">
	<div id="divTitrePageActuelle"><h1><a href="<?echo $lienPid;?>">Simulateurs - <span>Congés payés - Correspondance : jours ouvr&eacute;s / jours ouvrables</span></a></h1></div>
	<div id="itemlisteSimulateurs">
		<div class="itemlisteSimulateurs">
		<div class='exempleResultat'>
				<h4>Calcul en jours ouvrables ou en jours ouvr&eacute;s ?</h4>
				<div class="res">En g&eacute;n&eacute;ral, le nombre de jours de cong&eacute;s pay&eacute;s se comptabilise en jours ouvrables, soit tous les jours de la semaine &agrave; l'exception des dimanches et des jours f&eacute;ri&eacute;s ch&ocirc;m&eacute;s. <br/><br/>
				 Attention<br/>
				 Votre convention collective peut pr&eacute;voir un calcul en jours ouvr&eacute;s.  <br/><br/>
					Vous pouvez obtenir, ci dessous, l'&eacute;quivalence de jours ouvrables en jours ouvr&eacute;s et inversement. Cette table de concordance est calcul&eacute;e sur la base de 5 jours ouvr&eacute;s pour 6 jours ouvrables.
				  </div>	
		</div>	
			<form action="" id="f" method="post">
				<div>
					<h4>Concordance</h4><span></span><br />
					<div id="error"><?=$errorMessages?></div>
					<div><label for="nbjours1">Jours ouvrables (30 jours maxi)</label><input id="nbjours1" name="nbjours1" size="11" type="text" value="<?=$nbjours1?>" /> jour(s)</div><div style="clear:both;"></div>
					<div><label for="nbjours2">Jours ouvr&eacute;s (25 jours maxi)</label><input id="nbjours2" name="nbjours2" size="11" type="text" value="<?=$nbjours2?>" /> jour(s)</div><div style="clear:both;"></div>
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
