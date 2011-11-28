<?php
 
set_magic_quotes_runtime(0);
//require_once('nusoap-0.7.2/lib/nusoap.php');
require_once('library.php');

//Initialtion des cha?nes de messages utilisateur.
$errorMessages = "";		
$resultPhrase =  "";

//Extraction des variables post.
extract($_POST);

// Appel du service uniquement lorsque le formulaire a ?t? soumis.
if (!isSet($_POST['calculer'])) {
	
	//Initialisation des variables de formulaire
	$activite = "ventes";
	$perioderecette = "mensuelles";
	$montantrecettes = "";
}
else {

	//D?finition des param?tres d'appel du service depuis les variables post.
	$parameters = array(
		"activite" => $activite,
		"perioderecette" => $perioderecette,
		"montantrecettes" => $montantrecettes
	);
	


	//Invocation standard du service web : renvoit le tableau suivant : array($strResult, $errorMessages)
	$soapclient = new soapclient('http://www.tpe-pme.com/medias/simu-auto/server.php');
	$result = $soapclient->call('simuautoService', $parameters);
	$resultPhrase =  $result[0];
	$errorMessages = $result[1];
}
// Lien vers la page m�re PID
//$lienPid = $GLOBALS["TSFE"]->cObj->getTypolink_URL($GLOBALS["TSFE"]->page["pid"]);
?>

<div class="simulateur">
	<form action="" id="f" method="post">
	<h3>Calculez vos charges</h3>
<div id="error"><?=$errorMessages?></div>
	<div style="margin-top: 40px;"><fieldset>
		<legend>Quel type d�activit� exercez-vous ?</legend>
		<span class="label"><input name="activite" id="ventes" value="ventes" <?=($activite==ventes)?"checked=\"checked\"":""?> type="radio"> Ventes de marchandises, d'objets, de fournitures, de denr�es � emporter ou � consommer sur place, ou une activit� de fourniture de logement</span><br />
		<span class="label"><input name="activite" id="prestations" value="prestations" <?=($activite==prestations)?"checked=\"checked\"":""?> type="radio"> Prestations de services</span><br />
		<span class="label"><input name="activite" id="profession" value="profession" <?=($activite==profession)?"checked=\"checked\"":""?> type="radio"> Profession lib�rale relevant de la CIPAV</span>
		</fieldset>
	</div>		
	<div style="margin-top: 40px;">
	<fieldset><legend>Montant des recettes HT</legend>
		<div>   <span class="label"></span>
		<input gtbfieldid="230" name="montantrecettes" id="montantrecettes" size="11" value="<?=$montantrecettes?>" type="text">
	</div>
				<span class="label"><input name="perioderecette" id="mensuelles" value="mensuelles" <?=($perioderecette==mensuelles)?"checked=\"checked\"":""?> type="radio"> mensuelles</span><br />
		<span class="label"><input name="perioderecette" id="trimestrielles" value="trimestrielles" <?=($perioderecette==trimestrielles)?"checked=\"checked\"":""?> type="radio"> trimestrielles</span>
		</fieldset>
	</div>
	<div class="command"><input name="calculer" value="Calculer" type="submit">&nbsp;&nbsp;<input name="effacer" value="Effacer" type="reset">
	</div>

	
	</form>
</div>
<br>
<div class="simulateur">
	<span class="text"><?=$resultPhrase?></span>
</div>

