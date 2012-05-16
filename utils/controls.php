<?php
session_start();
/**
 * Ce fichier peut être appelé d'une page tierce avec:
 * <?php include "chemin_ver_le_fichier/controls.php"; ?>
 * 
 * Il déploie son css dans la page et accède aux fichiers utiles en utilisant le paramètre __DIR__
 * 
 */
//ini_set('display_errors', 1);
//error_reporting(E_ALL); 

/****************** PARAMETRES **********************/
$pageTitle = 'Admin sites v3';

switch ($_SERVER['SERVER_ADDR']) {
	case '127.0.0.1':
		$dirContentSites = '/data/www';
		$fileJsonData = '/data/www/siteData/dataSites.json';
		break;
	
	case '91.194.100.239':
		$dirContentSites = '/data/www/sitesv3';
		$fileJsonData = '/data/www/dataSites.json';		
		break;

	default:
		$dirContentSites = '/data/www';
		break;
}

// recherche de la commande php
$execOk = false;
$dirPhpCommand = '';
$dirPhpCommandPossibilities = array(
	'/opt/php/php5/cur/bin/',
	'',
	'/usr/bin/'
	);
foreach($dirPhpCommandPossibilities as $dirPhpCommandPossibility){
	if (is_file($dirPhpCommandPossibility.'php') && exec($dirPhpCommandPossibility.'php -v')){
			$dirPhpCommand = $dirPhpCommandPossibility;
			$execOk = true;
		}
}
if (!$execOk) echo '<ul><li class="denied">Impossible de trouver la commande PHP</li></ul>';

// le mot de passe
$loginPassword = 'comme une fleur en hiver';

?>

<html lang="fr" > 
<head>
<meta charset="utf-8" />
<title><?php echo $pageTitle; ?></title>
<meta name="language" content="fr" />
<style>
	<?php echo file_get_contents(__DIR__."/controls.css"); ?>
	<?php echo file_get_contents(__DIR__."/icones.css"); ?>
</style>
</head>


<style type="text/css" media="all">
#chargement{
	top:0;
	left:0;
	width:100%;
	height:100%;
    	position: fixed;
	background-color: gray;
  	opacity : 0.7;
  	-moz-opacity : 0.7;
  	-ms-filter: "alpha(opacity=70)"; 
  	filter : alpha(opacity=70);
	z-index: 10000;
}
#chargement div.load{
	text-align: center;
	width: 400px;
	color: #FFFFFF;
	font-weight: bold;
	display: block;
	position:absolute;
	top: 50%;
	left: 50%; 
	margin-left: -200px;
}
</style>
<script type="text/javascript">
document.getElementByiId('chargement').style.display='block';
function displayLoad()
{
     document.getElementById('chargement').style.display='block';
}
function hideLoad()
{
     document.getElementById('chargement').style.display='none';
}
</script>
<body onload="hideLoad();">
<div id="chargement"><div class="load">Veuillez patienter, chargement en cours...</div></div>


<?php

$messageLogin = '';

if (isset($_POST['deconnexion'])){
	$_SESSION['loginOK']= 'ko';
}
if (isset($_POST['login'])){
	$login = $_POST['login'];
	if ($login==$loginPassword){
		$_SESSION['loginOK'] = 'ok';
	} else {
		$messageLogin = '<br/><br/><ul><li class="denied">Accès refusé</li></ul>';
	}
}

if (isset($_SESSION['loginOK']) && $_SESSION['loginOK']== 'ok'){

	/************************************************************************************************************************************
	*********************************************** Formulaire ***********************************************************************
	************************************************************************************************************************************/

	$commandToRun = '';
	$reloadData = '';
	$results = false;

	// deconnexion
	echo '<ul class="deconnex"><li class="unlock"><a onclick="document.deconnex.submit();" href="#">Déconnexion</a></li></ul>';
	// affichage

	echo '<div class="promptFrame">';
	echo '<h1>'.$pageTitle.'</h1>';

	// formulaire pour la recherche de ndd
	echo '<form method="post" onSubmit="displayLoad();" action="'.$_SERVER['PHP_SELF'].'">';
	echo '	<ul><li class="search"><a href="#">Recherche de site</a></li></ul> <input type="text" name="site" />';
	echo '  <span class="infos">Par nom de domaine, type, thème...</span>';
	echo '</form>';

	// recherche 
	if (isset($_POST['site'])){
		$site = $_POST['site'];
		$arraySites = json_decode(file_get_contents($fileJsonData),true);
		$findRes = false;  // a t on au moins un resultat?

		foreach ($arraySites as $key => $arraySite) {
			$siteMatch = false;
			foreach ($arraySite as $line => $value) {
					$pos = strpos(removeAccents(strtolower($value)), removeAccents(strtolower($site))); // on fait une recherche de chaine formatée (minuscule sans accent) pour avoir un maximum de résultat
					if ($pos === false) {
					} else {
						$siteMatch = true; // le site en cours correspond 
					}
			}
			if ($siteMatch) { // on affiche le site complet en cours
				 foreach ($arraySite as $data) {
				 	echo $data;
				 }
				$findRes = true;
			}
		}
		if (!$findRes) echo '<ul><li class="denied">Pas de résultat</li></ul>';
	}
	if ($results) echo '> '.$results; 

	echo '</div>';

	// formulaire pour la recharge
	if (isset($_POST['reloadData'])){
		$reloadData = $_POST['reloadData'];
		if (executeCommand($fileJsonData)) {
			//echo '<ul><li class="info"><a href="#">Chargement des données ok</a></li></ul>';
		}
	}
	echo '<form class="reload" method="post" name="reloadData" onSubmit="displayLoad();" action="'.$_SERVER['PHP_SELF'].'">';
	echo '	<ul><li class="repeat"><a onClick="displayLoad();document.reloadData.submit();" href="#">Recharger les données</a></li></ul>';
	echo '  <span class="infos">dernière mise à jour le '.date("Y-m-d à H:i:s",filemtime($fileJsonData)).'</span>';
	echo '	<input type="hidden" name="reloadData" value="true"/>';	
	echo '</form>';

	// deconnexion
	echo '<form name="deconnex" class="deconnexion" method="post" action="'.$_SERVER['PHP_SELF'].'">';
	echo '<input type="hidden" name="deconnexion" value="ok" />';
	echo '</form>';	
} else {

	echo '<div class="promptFrame login">';
	echo '<h1>'.$pageTitle.'</h1>';
	// formulaire pour la commande
	echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'">';
	echo '	<ul><li class="lock"><a href="#">Connexion</a></li></ul> <input type="text" name="login" />';
	//echo '	<input class="submit" type="submit" value="Ok" />';
	echo $messageLogin;
	echo '</form>';
	echo '</div>';
}



/************************************************************************************************************************************
*********************************************** Les fonctions ***********************************************************************
************************************************************************************************************************************/

/**
 * execute la commande et renvoie une chaine de resultat
 * @param  [type] $command [description]
 * @return [type]          [description]
 */
function executeCommand($file=false){
	global $dirContentSites;
	global $dirPhpCommand;
	
	$resultRaw = '';
	$result = array();
	$promptOnHtml = '';
	$arrayPromptOnHtml = array();

//debug
//exec('/opt/php/php5/cur/bin/'.$command. ' 2>&1', $result);
//echo $command."\n";
//echo exec($command. ' 2>&1', $result);
	if (!$file){
		exec($command, $result);
		foreach ($result as $key => $value) {
		    // traitement des codes du prompt pour remplacement en HTML
		    $resultRaw .= $value;
		    $promptOnHtml .= promptToHtml($value);
		}
		//return '>>'.$resultRaw.'<<   '.$promptOnHtml;
		if ($promptOnHtml == ''){
			$promptOnHtml = '<ul><li class="denied">Pas de résultat</li></ul>';
		}
		return $promptOnHtml;
	} else {
		if (is_file($file)){
			//unlink($file);
			$inF = fopen($file,"w");
			ftruncate($inF,0);
		} else {
			$inF = fopen($file,"w");
		}
		// on crée le fichier
		$inF = fopen($file,"w");

		// on se ballade dans tous les sites pour lancer la task dm:infos-site
		$i=0;
		$dirContent = opendir($dirContentSites); 
		while($dir = readdir($dirContent)) {
			$command = $dirPhpCommand.'php '.$dirContentSites.'/'.$dir.'/symfony dm:infos-site';  // on envoie '.' à la tache is-ndd (via controls) car tous les ndd ont un '.'
			//echo $command.'<br/>';
			$result = array();
			exec($command, $result);

			foreach ($result as $key => $value) {
			    // traitement des codes du prompt pour remplacement en HTML
			    $resultRaw .= $value;
			    $arrayPromptOnHtml[$i][] = promptToHtml($value);
			}
			$i++;
		}
		//fputs($inF,$command);
		// on met tous les résultats de la tache infos-site dans un fichier json
		fputs($inF,json_encode($arrayPromptOnHtml));
		fclose($inF);

		return true;
	}
}

/**
 * convert string in prompt unix terminal type into HTML
 * @param  [type] $value [description]
 * @return [type]        [description]
 */
function promptToHtml($value) {
	$class = array();
	$promptCode['30']  = 'black';
	$promptCode['31']  = 'red';
	$promptCode['32']  = 'green';
	$promptCode['33']  = 'yellow';
	$promptCode['34']  = 'blue';
	$promptCode['35']  = 'purple';
	$promptCode['36']  = 'cyan';
	$promptCode['37']  = 'white';
	$promptCode['90']  = 'lightBlack';
	$promptCode['91']  = 'lightRed';
	$promptCode['92']  = 'lightGreen';
	$promptCode['93']  = 'lightYellow';
	$promptCode['94']  = 'lightBlue';
	$promptCode['95']  = 'lightPurple';
	$promptCode['96']  = 'lightCyan';
	$promptCode['97']  = 'lightWhite';
	$promptCode['40']  = 'bgBlack';
	$promptCode['41']  = 'bgRed';
	$promptCode['42']  = 'bgGreen';
	$promptCode['43']  = 'bgYellow';
	$promptCode['44']  = 'bgBlue';
	$promptCode['45']  = 'bgPurple';
	$promptCode['46']  = 'bgCyan';
	$promptCode['47']  = 'bgWhite';
	$promptCode['100'] = 'lightBgBlack';
	$promptCode['101'] = 'lightBgRed';
	$promptCode['102'] = 'lightBgGreen';
	$promptCode['103'] = 'lightBgYellow';
	$promptCode['104'] = 'lightBgBlue';
	$promptCode['105'] = 'lightBgPurple';
	$promptCode['106'] = 'lightBgCyan';
	$promptCode['107'] = 'lightBgWhite';
	$promptCode['0']   = 'regular';
	$promptCode['1']   = 'bold';
	$promptCode['4']   = 'underline';
    // suppression des char ESC
    $value = str_replace(chr(27) , '', $value);
    // suppression des [0m parasite
    $value = str_replace('[0m', '', $value);
    // on cherche les chaines du type "[30;36m" , "[0;37;45m" ... etc
    preg_match_all("/\[[0-9;]*m/", $value, $matches) . '<br/>';
    // loop through the matches with foreach
    
    foreach ($matches[0] as $match) {
        $value = str_replace($match, '', $value); // suppression de la chaine trouvée
        $match = str_replace('[', '', $match);
        $match = str_replace('m', '', $match);
        $listOfInt = explode(';', $match);
        
        foreach ($listOfInt as $int) {
            //$value.= ' >' . $int;
            $class[] = $promptCode[$int];
        }
    }
    $class = array_unique($class);
    $classList = implode(' ', $class);
    
    if ($value == ''){
    	$return = '';
    } else {
    	$return = '<p class ="' . $classList . '">' . $value . '</p>';
    }

    return $return;
}

function removeAccents($txt) {
    $txt = str_replace('œ', 'oe', $txt);
    $txt = str_replace('Œ', 'Oe', $txt);
    $txt = str_replace('æ', 'ae', $txt);
    $txt = str_replace('Æ', 'Ae', $txt);
    mb_regex_encoding('UTF-8');
    $txt = mb_ereg_replace('[ÀÁÂÃÄÅĀĂǍẠẢẤẦẨẪẬẮẰẲẴẶǺĄ]', 'A', $txt);
    $txt = mb_ereg_replace('[àáâãäåāăǎạảấầẩẫậắằẳẵặǻą]', 'a', $txt);
    $txt = mb_ereg_replace('[ÇĆĈĊČ]', 'C', $txt);
    $txt = mb_ereg_replace('[çćĉċč]', 'c', $txt);
    $txt = mb_ereg_replace('[ÐĎĐ]', 'D', $txt);
    $txt = mb_ereg_replace('[ďđ]', 'd', $txt);
    $txt = mb_ereg_replace('[ÈÉÊËĒĔĖĘĚẸẺẼẾỀỂỄỆ]', 'E', $txt);
    $txt = mb_ereg_replace('[èéêëēĕėęěẹẻẽếềểễệ]', 'e', $txt);
    $txt = mb_ereg_replace('[ĜĞĠĢ]', 'G', $txt);
    $txt = mb_ereg_replace('[ĝğġģ]', 'g', $txt);
    $txt = mb_ereg_replace('[ĤĦ]', 'H', $txt);
    $txt = mb_ereg_replace('[ĥħ]', 'h', $txt);
    $txt = mb_ereg_replace('[ÌÍÎÏĨĪĬĮİǏỈỊ]', 'I', $txt);
    $txt = mb_ereg_replace('[ìíîïĩīĭįıǐỉị]', 'i', $txt);
    $txt = str_replace('Ĵ', 'J', $txt);
    $txt = str_replace('ĵ', 'j', $txt);
    $txt = str_replace('Ķ', 'K', $txt);
    $txt = str_replace('ķ', 'k', $txt);
    $txt = mb_ereg_replace('[ĹĻĽĿŁ]', 'L', $txt);
    $txt = mb_ereg_replace('[ĺļľŀł]', 'l', $txt);
    $txt = mb_ereg_replace('[ÑŃŅŇ]', 'N', $txt);
    $txt = mb_ereg_replace('[ñńņňŉ]', 'n', $txt);
    $txt = mb_ereg_replace('[ÒÓÔÕÖØŌŎŐƠǑǾỌỎỐỒỔỖỘỚỜỞỠỢ]', 'O', $txt);
    $txt = mb_ereg_replace('[òóôõöøōŏőơǒǿọỏốồổỗộớờởỡợð]', 'o', $txt);
    $txt = mb_ereg_replace('[ŔŖŘ]', 'R', $txt);
    $txt = mb_ereg_replace('[ŕŗř]', 'r', $txt);
    $txt = mb_ereg_replace('[ŚŜŞŠ]', 'S', $txt);
    $txt = mb_ereg_replace('[śŝşš]', 's', $txt);
    $txt = mb_ereg_replace('[ŢŤŦ]', 'T', $txt);
    $txt = mb_ereg_replace('[ţťŧ]', 't', $txt);
    $txt = mb_ereg_replace('[ÙÚÛÜŨŪŬŮŰŲƯǓǕǗǙǛỤỦỨỪỬỮỰ]', 'U', $txt);
    $txt = mb_ereg_replace('[ùúûüũūŭůűųưǔǖǘǚǜụủứừửữự]', 'u', $txt);
    $txt = mb_ereg_replace('[ŴẀẂẄ]', 'W', $txt);
    $txt = mb_ereg_replace('[ŵẁẃẅ]', 'w', $txt);
    $txt = mb_ereg_replace('[ÝŶŸỲỸỶỴ]', 'Y', $txt);
    $txt = mb_ereg_replace('[ýÿŷỹỵỷỳ]', 'y', $txt);
    $txt = mb_ereg_replace('[ŹŻŽ]', 'Z', $txt);
    $txt = mb_ereg_replace('[źżž]', 'z', $txt);
    
    return $txt;
    }

  ?>

</body>
</html>