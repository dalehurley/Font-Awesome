<html class="no-js" lang="fr" > 
<head>
<meta charset="utf-8" />
<title>Site V3 - controls</title>
<meta name="language" content="fr" />
<link rel="stylesheet" media="all" href="controls.css" />
<link rel="shortcut icon" href="favicon.png" />
</head>

<body>

<?php
echo 'controls';

$commandToRun = 'php /data/www/_lib/diem/utils/controls /data/www s* infos-site auto quiet';
$result = array();
exec($commandToRun, $result);

foreach ($result as $key => $value) {
    // traitement des codes du prompt pour remplacement en HTML
    echo promptToHtml($value);
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