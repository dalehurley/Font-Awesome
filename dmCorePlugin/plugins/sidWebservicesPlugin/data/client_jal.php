<?php //bibliotheque
/*
 * Utilisation du service web jalService
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
	$result = $soapclient->call('jalService', $parameters);		
	$resultPhrase =  $result[0];
	$errorMessages = $result[1];
}
?>
<div id="centreContenu">
<div id="simulateursCalculs">
	<div id="divTitrePageActuelle"><h1>Simulateurs - <span>Journaux d'annonces l&eacute;gales</span></h1></div>
	<div id="itemlisteSimulateurs">
		<div class="itemlisteSimulateurs">
		<div class='exempleResultat'>
				<div class="res">Pour trouver un journal d'annonces l&eacute;gales, choisissez le d&eacute;partement de votre localit&eacute;.
				</div>	
		</div>	
			<form action="" id="f" method="post">
				<div>
					<div id="error"><?=$errorMessages?></div>
					<div>
						<label for="departement">S&eacute;lectionnez le num&eacute;ro de votre d&eacute;partement</label>
						<select name="departement" id="departement">
						 <option selected="" > </option>
							  <?php
							  //connexion au serveur:
								//$cnx = mysql_connect("mysql2.nuxit.net", "gaonach", "lPOj2zja") or die( "connect : ERREUR MYSQL numéro: ".mysql_errno()."<br>Type de cette erreur: ".mysql_error()."<br>\n" );
								$cnx = mysql_connect("mysql2.nuxit.net", "gaonach", "lPOj2zja");
								//sélection de la base de données:
								$db= mysql_select_db( "gaonach_tpepme", $cnx );
								//création de la requête SQL:
								$sql = "SELECT no_dept,nom_dept FROM dept ORDER BY nom_dept";
								//$requete = mysql_query( $sql, $cnx ) or die( "ERREUR MYSQL numéro: ".mysql_errno()."<br>Type de cette erreur: ".mysql_error()."<br>\n" );
								$requete = mysql_query( $sql, $cnx );
								
							  while( $result = mysql_fetch_array( $requete ) )
								{
									$departement=($departement == $result["no_dept"])?"selected":"";
									echo( "<option value=".$result["no_dept"]." ".$departement.">".$result["nom_dept"]."</option>" );
								  
								}
							  ?>
						</select>
					</div>
					<div style="clear:both;"></div>
					<div class="divBoutons">
						<button type="submit" name="calculer">Calculer</button>&nbsp;&nbsp;<button type="reset" name="effacer">Effacer</button>
					</div>
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
