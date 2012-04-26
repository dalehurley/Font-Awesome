<?php
/**
 * Ce fichier peut être appelé d'une page tierce avec:
 * <?php include "chemin_ver_le_fichier/controls.php"; ?>
 * 
 * Il déploie son css dans la page et accède aux fichiers utiles en utilisant le paramètre __DIR__
 * 
 */

switch ($_SERVER['REMOTE_ADDR']) {
	case '127.0.0.1':
		$dirContentSites = '/data/www';
		break;
	
	case '91.194.100.239':
		$dirContentSites = '/data/www/sitesv3';
		break;

	default:
		$dirContentSites = '/data/www';
		break;
}

$loginPassword = 'comme une fleur en hiver';

?>

<html lang="fr" > 
<head>
<meta charset="utf-8" />
<title>Sites V3 - controls</title>
<meta name="language" content="fr" />
<style>
	<?php echo file_get_contents(__DIR__."/controls.css"); ?>
</style>
</head>
<body>

<?php
session_start();

if (isset($_POST['deconnexion'])){
	$_SESSION['loginOK']= 'ko';
}
if (isset($_POST['login'])){
	
	$login = $_POST['login'];
	if ($login==$loginPassword){
		$_SESSION['loginOK'] = 'ok';
	}
}

if (isset($_SESSION['loginOK']) && $_SESSION['loginOK']== 'ok'){

	/************************************************************************************************************************************
	*********************************************** Formulaire ***********************************************************************
	************************************************************************************************************************************/

	// recherche par nom de domaine
	if (isset($_POST['site'])){
		$site = $_POST['site'];
		$commandToRun = 'php '.__DIR__.'/controls '.$dirContentSites.' --all ndd='.$site.' auto quiet';
	}

	// affichage
	echo '<div class="promptFrame">';
	echo '<h1>Sites V3</h1>';
	// formulaire pour la commande
	echo '<form method="post" action="'.$PHP_SELF.'">';
	echo '	Recherche par nom de domaine <input type="text" name="site" />';
	echo '</form>';

	if ($res = executeCommand($commandToRun)) echo $res;
	echo '</div>';

	// deconnexion
	echo '<form class="deconnexion" method="post" action="'.$PHP_SELF.'">';
	echo '<input type="hidden" name="deconnexion" value="ok" />';
	echo '<input class="deconnexion" type="submit" value="déconnexion" />';
	echo '</form>';	
} else {

	echo '<div class="promptFrame">';
	echo '<h1>Sites V3</h1>';
	// formulaire pour la commande
	echo '<form method="post" action="'.$PHP_SELF.'">';
	echo '	Phrase de connexion <input type="text" name="login" />';
	echo '	<input class="submit" type="submit" value="Ok" />';
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
function executeCommand($command){
	$result = array();
	$promptOnHtml = '';

	exec(escapeshellcmd($command), $result);

	foreach ($result as $key => $value) {
	    // traitement des codes du prompt pour remplacement en HTML
	    $resultRaw .= $value;
	    $promptOnHtml .= promptToHtml($value);
	}

	//return '>>'.$resultRaw.'<<   '.$promptOnHtml;
	return $promptOnHtml;
}

/**
 * convert string in prompt unix terminal type into HTML
 * @param  [type] $value [description]
 * @return [type]        [description]
 */
function promptToHtml($value) {
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
    
    return '<p class ="' . $classList . '">' . $value . '</p>';
}
  ?>

</body>
</html>