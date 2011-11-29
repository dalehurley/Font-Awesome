<?php //bibliotheque
/*
 * Utilisation du service web vtService
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
	$departement = "";	
	}
else {
	//D&eacute;finition des param&egrave;tres d'appel du service depuis les variables post.
	$parameters = array(
		"departement" => $departement
	);
	
	//Invocation standard du service web : renvoit le tableau suivant : array($strResult, $errorMessages)
	$soapclient = new soapclient($SOAP_SERVER_URL);
	$result = $soapclient->call('vtService', $parameters);		
	$resultPhrase =  $result[0];
	$errorMessages = $result[1];
}
?>

<div id="centreContenu">
<div id="simulateursCalculs">
	<div id="divTitrePageActuelle"><h1>Simulateurs - <span>Versement transport</span></h1></div>
	<div id="itemlisteSimulateurs">
		<div class="itemlisteSimulateurs">
		<div class='exempleResultat'>
				<div class="res"> Les employeurs occupant plus de 9 salari&eacute;s dans certaines communes ou groupements de communes (districts, communaut&eacute;s urbaines, communaut&eacute;s de villes ou de communes, etc...) de plus de 10 000 habitants sont redevables d'un versement destin&eacute; aux transports en commun, assis sur les r&eacute;mun&eacute;rations soumises &agrave; cotisations de s&eacute;curit&eacute; sociale dans la limite du plafond.<br/><br/>
				Pour conna&icirc;tre le taux de versement transport applicable dans votre ville :
				</div>	
		</div>	
			<form action="" id="f" method="post">
				<div><div id="error"><?=$errorMessages?></div>
					<div><label for="departement">S&eacute;lectionnez le num&eacute;ro de votre d&eacute;partement</label>
					<select name="departement" id="departement">
					  <?php
					   for($i=1; $i<=97; $i++)
						{ 
						  if ($i!=96 && $i!=9 && $i!=23 && $i!=39)
						  {
							$departement=($departement == '$i')?"selected":"";
							echo( "<option value=".$i." ".$departement.">".$i."</option>" );
						  }
						}
					  ?>
					</select></div><div style="clear:both;"></div>
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
