<?php //bibliotheque
/*
 * Utilisation du service web fkService
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
	$puissance = "";
	$distance = "";	
	}
else {
	//D&eacute;finition des param&egrave;tres d'appel du service depuis les variables post.
	$parameters = array(
		"puissance" => $puissance,
		"distance" => $distance
	);
	
	//Invocation standard du service web : renvoit le tableau suivant : array($strResult, $errorMessages)
	$soapclient = new soapclient($SOAP_SERVER_URL);

	$result = $soapclient->call('fkService', $parameters);		

	$resultPhrase =$result[0];
	$errorMessages = $result[1];
}
?>

<div id="centreContenu">
<div id="simulateursCalculs">
	<div id="divTitrePageActuelle"><h1>Simulateurs - <span>Calculs frais kilom&eacute;triques - v&eacute;hicules automobiles</span></h1></div>
	<div id="itemlisteSimulateurs">
		<div class="itemlisteSimulateurs">
			<form action="" id="f" method="post">
				<div><div id="error"><?=$errorMessages?></div>
					<div><label for="marge">Puissance administrative</label>
					<select name="puissance" id="puissance">
						<option value="3" <?=($puissance == '3')?"selected":""?>>3 CV et moins</option>
						<option value="4"  <?=($puissance ==  '4')?"selected":""?>>4 CV</option>
						<option value="5"  <?=($puissance ==  '5')?"selected":""?>>5 CV</option>
						<option value="6"  <?=($puissance ==  '6')?"selected":""?>>6 CV</option>
					    <option value="7"  <?=($puissance ==  '7')?"selected":""?>>7 CV</option>
						<option value="8"  <?=($puissance ==  '8')?"selected":""?>>8 CV</option>
						<option value="9"  <?=($puissance ==  '9')?"selected":""?>>9 CV</option>
					    <option value="10"  <?=($puissance ==  '10')?"selected":""?>>10 CV</option>
						<option value="11"  <?=($puissance ==  '11')?"selected":""?>>11 CV</option>
						<option value="12"  <?=($puissance ==  '12')?"selected":""?>>12 CV</option>
						<option value="13"  <?=($puissance ==  '13')?"selected":""?>>13 CV et plus</option>
					</select></div><div style="clear:both;"></div>
					<div><label for="distance">Distance parcourue</label><input id="distance" name="distance" size="11" type="text" value="<?=$distance?>" /></div><div style="clear:both;"></div>
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