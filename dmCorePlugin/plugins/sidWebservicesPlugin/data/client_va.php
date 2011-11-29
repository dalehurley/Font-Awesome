<?php
/*
 * Utilisation du service web vaService
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

//Initialtion des chaînes de messages utilisateur.
$errorMessages = "";		
$resultPhrase =  "";

//Extraction des variables post.
extract($_POST);

// Appel du service uniquement lorsque le formulaire a &eacute;t&eacute; soumis.
if (!isSet($_POST['calculer'])) {
	
	//Initialisation des variables de formulaire
	$departement = "";
	$puissance = "";
	$jour = "";
  $mois = "";
  $annee = "";
	$genre = 1;
	$fonctionnement = 1;
}
else {

	//D&eacute;finition des param&egrave;tres d'appel du service depuis les variables post.
	$parameters = array(
		"departement" => $departement,
		"puissance" => $puissance,
		"jour" => $jour,
    "mois" => $mois,
    "annee" => $annee,
		"genre" => $genre,
		"fonctionnement" => $fonctionnement
	);
	
	//Invocation standard du service web : renvoit le tableau suivant : array($strResult, $errorMessages)
	$soapclient = new soapclient($SOAP_SERVER_URL);
	$result = $soapclient->call('vaService', $parameters);		
	$resultPhrase =  $result[0];
	$errorMessages = $result[1];
}
?>
<div id="centreContenu">
<div id="simulateursCalculs">
	<div id="divTitrePageActuelle"><h1>Simulateurs - <span>Vignette automobile</span></h1></div>
	<div id="itemlisteSimulateurs">
		<div class="itemlisteSimulateurs">
			<form action="" id="f" method="post">
				<div>
					<div id="error"><?=$errorMessages?></div>
					<div><label for="departement">D&eacute;partement</label><input id="departement" name="departement" size="11" type="text" value="<?=$departement?>" /></div><div style="clear:both;"></div>
					<div><label for="puissance">Puissance (en CV)</label><input id="puissance" name="puissance" size="5" type="text" value="<?=$puissance?>" /></div><div style="clear:both;"></div>
					<div><label for="circulation">Mise en circulation </label> Jour<input name="jour" id="jour" size="2" type="text" value="<?=$jour?>" /> Mois<input name="mois" id="mois" size="2" type="text" value="<?=$mois?>" /> Année<input name="annee" id="annee" size="4" type="text" value="<?=$annee?>" /></div><div style="clear:both;"></div>
					<fieldset>
						<legend>Genre du v&eacute;hicule</legend>
						<label for="genre"><input type="radio" name="genre" id="vp" value="1" <?=($genre==true)?"checked=\"checked\"":""?> /> Personnel (VP)</label><label for="vu"><input type="radio" name="genre" id="vu" value="0" <?=($genre==false)?"checked=\"checked\"":""?> /> Utilitaire (VU)</label>
					</fieldset><div style="clear:both;"></div>
					<fieldset>
						<legend>Fonctionnement</legend>
						<label for="normal"><input type="radio" name="fonctionnement" id="normal" value="1" <?=($fonctionnement==true)?"checked=\"checked\"":""?> /> normal</label><label for="autres"><input type="radio" name="fonctionnement" id="autres" value="0" <?=($fonctionnement==false)?"checked=\"checked\"":""?> /> GPL, &eacute;lectricit&eacute; ou gaz naturel</label>
					</fieldset><div style="clear:both;"></div>
					<div style="clear:both;"></div>
					<div class="divBoutons"><button type="submit" name="calculer">Calculer</button>&nbsp;&nbsp;<button type="reset" name="effacer">Effacer</button></div>
					<div style="clear:both;"></div>
				</div>
			</form>
			<p><?=$resultPhrase?></p>
			<br />
			<div class='exempleResultat'>
				<div class="res">La vignette reste applicable en principe &agrave; tous les v&eacute;hicules (voitures particuli&egrave;res ou v&eacute;hicules utilitaires) appartenant aux soci&eacute;t&eacute;s (SA, SARL, EURL...), aux &eacute;tablissements publics, aux collectivit&eacute;s territoriales et &agrave; l'Etat.<br/><br/>
				  Toutefois, les soci&eacute;t&eacute;s b&eacute;n&eacute;ficient d'une exon&eacute;ration de vignette pour les v&eacute;hicules dont le poids total autoris&eacute; en charge (PTAC) est inf&eacute;rieur &agrave; 3,5 tonnes, dans la limite de trois v&eacute;hicules. Cette franchise s'applique au niveau national, et non par d&eacute;partement d'immatriculation.<br/><br/>
				  Pour les personnes physiques et les associations, la vignette n'est due en revanche que pour les v&eacute;hicules utilitaires dont le PTAC est sup&eacute;rieur &agrave; 3,5 tonnes.<br/><br/>
				  A noter :<br>
				  Certains v&eacute;hicules b&eacute;n&eacute;ficient d'une exon&eacute;ration sp&eacute;ciale de vignette. C'est notamment le cas des v&eacute;hicules appartenant aux VRP (exon&eacute;ration limit&eacute;e &agrave; un seul v&eacute;hicule), ainsi que des v&eacute;hicules non polluants, fonctionnant exclusivement ou non &agrave; l'&eacute;lectricit&eacute;, au GPL ou au GNV, sur d&eacute;cision du Conseil G&eacute;n&eacute;ral du lieu de leur immatriculation.</span></div><div style="clear:both;"></div>
					<div style="clear:both;"></div>
									<table border="1" cellpadding="2" cellspacing="0" width="100%" class="table1">
											<caption>Tableau r&eacute;capitulatif des nouveaux cas d'exon&eacute;rations</caption>
											<thead>
												<tr>
													<th id="thTitre1">Nature du V&eacute;hicule<br>dont la personne est propri&eacute;taire ou locataire en vertu d'un contrat de cr&eacute;dit-bail ou de location de deux ans ou plus</th>
													<th id="thTitre2">Vous &ecirc;tes une personne physique,<br>une association, congr&eacute;gation, fondation, fondation d'entreprise, syndicat professionnel</th>
													<th id="thTitre3">Vous &ecirc;tes une autre personne morale<br>(soci&eacute;t&eacute;, collectivit&eacute; territoriale.)</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td headers="thTitre1">Une voiture particuli&egrave;re : Genre « VP », et plus g&eacute;n&eacute;ralement, un v&eacute;hicule dont le poids total autoris&eacute; en charge n'exc&egrave;de pas 3,5 tonnes (poids TC en kg &lt; 3 500), quel que soit le genre</td>
													<td headers="thTitre2">Exon&eacute;r&eacute;</td>
													<td headers="thTitre3" rowspan="2" align="left">Exon&eacute;r&eacute; dans la limite de trois de ces v&eacute;hicules par p&eacute;riode d'imposition</td>
												</tr>
												<tr>
													<td headers="thTitre1">Un v&eacute;hicule sp&eacute;cialement am&eacute;nag&eacute; pour le transport des personnes handicap&eacute;es, ou un camping-car, de poids total autoris&eacute; en charge sup&eacute;rieur &agrave; 3,5 tonnes :<br>&#150; Genre « VASP », et carrosserie « caravane » ou « handicap »<br>&#150; Genre « VTSU » et carrosserie « caravane », « roulotte habitable » ou « handicap&eacute;s »</td>
													<td headers="thTitre2">Exon&eacute;r&eacute;</td>
												</tr>
												<tr>
													<td headers="thTitre1">Un autre v&eacute;hicule que ceux mentionn&eacute;s pr&eacute;c&eacute;demment (ex. genre CAM)</td>
													<td headers="thTitre2">Redevable, sauf si le v&eacute;hicule b&eacute;n&eacute;ficiait ant&eacute;rieurement d'une exon&eacute;ration</td>
													<td headers="thTitre3">Redevable</td>
												</tr>
											</tbody>
										</table>
										<div style="clear:both;"></div>

				</div>
			</div>
			<div style="clear:both;"></div>
		</div>
		<div style="clear:both;"></div>
	</div>
</div>
</div>