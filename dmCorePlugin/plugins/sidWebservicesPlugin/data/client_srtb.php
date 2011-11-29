<?php //bibliotheque
/*
 * Utilisation du service web vacpicddService
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
	$marge = "";
	$chargevar = "";
	$chargestruct = "";
	$remuneration = "";
	$ca = "";
	
	}
else {
	//D&eacute;finition des param&egrave;tres d'appel du service depuis les variables post.
	$parameters = array(
		"marge" => $marge,
		"chargevar" => $chargevar,
		"chargestruct" => $chargestruct,
		"remuneration" => $remuneration,
		"ca" => $ca
	);
	
	//Invocation standard du service web : renvoit le tableau suivant : array($strResult, $errorMessages)
	$soapclient = new soapclient($SOAP_SERVER_URL);
	$result = $soapclient->call('srtbService', $parameters);		
	$resultPhrase =  $result[0];
	$errorMessages = $result[1];
}
?>
<div id="centreContenu">
<div id="simulateursCalculs">
	<div id="divTitrePageActuelle"><h1>Simulateurs - <span>Seuil de rentabilit&eacute;</span></h1></div>
	<div id="itemlisteSimulateurs">
		<div class="itemlisteSimulateurs">
		<div class='exempleResultat'>
				<h4>Estimation rapide</h4>
				<div class="res">Le seuil de rentabilit&eacute; est le chiffre d'affaires (CA) pour lequel le r&eacute;sultat est &eacute;gal &agrave; 0, c'est-&agrave;-dire le CA minimum qu'il vous faut r&eacute;aliser pour ne pas perdre d'argent ou le CA &agrave; partir duquel votre projet devient b&eacute;n&eacute;ficiaire.
				<br/><br/>
				Indice de s&eacute;curit&eacute; : c'est le rapport entre votre chiffre d'affaires et le seuil de rentabilit&eacute;.<br/>
				Exemple : 26 000/15 250 : l'indice de s&eacute;curit&eacute; = 1,70.
				<br/><br/>
				La marge de s&eacute;curit&eacute; s'&eacute;l&egrave;ve &agrave; 10 750 euros, votre chiffre d'affaires peut baisser de 10 750 euros avant que vous ne soyez en perte.
				<br/><br/>
				Pour effectuer ce calcul, vous devez conna&icirc;tre le taux de marge brute d&eacute;gag&eacute; par votre exploitation, le pourcentage de charges variables g&eacute;n&eacute;r&eacute; par l'exploitation, le volume de charges de structure (celles qui sont fixes, quel que soit le niveau du chiffre d'affaires) et le chiffre d'affaires annuel r&eacute;alis&eacute;.
				<br/><br/>
				  Si vous &ecirc;tes en entreprise individuelle, vous devez &eacute;valuer votre niveau de r&eacute;mun&eacute;ration.
				  </div>	
		</div>	
			<form action="" id="f" method="post">
				<div>
					<h4>Saisissez vos donn&eacute;es</h4><span></span><br />
					<div id="error"><?=$errorMessages?></div>
					<div><label for="marge">Taux de marge brute (en % du CA HT)</label><input id="marge" name="marge" size="11" type="text" value="<?=$marge?>" /></div><div style="clear:both;"></div>
					<div><label for="chargevar">Charges variables (en % du CA HT)</label><input id="chargevar" name="chargevar" size="11" type="text" value="<?=$chargevar?>" /></div><div style="clear:both;"></div>
					<div><label for="chargestruct">Charges de structures annuelles (en euros HT) </label><input name="chargestruct" id="chargestruct" size="11" type="text" value="<?=$chargestruct?>" /></div><div style="clear:both;"></div>
					<div style="clear:both;"></div>
					<div><label for="remuneration">Rémunération annuelle de l'exploitant (en €) (seulement pour les entreprises individuelles)</label><input id="remuneration" name="remuneration" size="11" type="text" value="<?=$remuneration?>" /></div><div style="clear:both;"></div>
					<div><label for="ca">Chiffre d'Affaires annuel réalisé actuellement</label><input id="ca" name="ca" size="11" type="text" value="<?=$ca?>" /></div><div style="clear:both;"></div>
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
