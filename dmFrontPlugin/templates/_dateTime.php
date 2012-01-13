<?php
/*
 * _dateTime.php
 * v0.1
 * Permet d'afficher une balise Time avec différentes options.
 * 
 * Variables disponibles :
 *	date		date ISO sous la forme 2012-01-05 11:38:14
 *	type		indique le type de date : created, updated
 *	format		indique le parttern d'affichage
 * 
 * Pour info : http://blog.rajatpandit.com/2009/01/28/datehelper-date_format-in-symfony/
 */

//création des options à rajouter à l'élément time
$timeOptions = array('datetime' => $date);

//type de date
switch ($type) {
	case 'created':
		$dateTypeClass = 'datePublished';
		$timeOptions['pubdate'] = "pubdate";
		break;
	case 'updated':
		$dateTypeClass = 'dateModified';
		break;
	default:
		$dateTypeClass =  null;
		break;
}
if($dateTypeClass) {
	$timeOptions['class'] = $dateTypeClass;
	$timeOptions['itemprop'] = $dateTypeClass;
}

//formattage de la date
$dateFormat = format_date($date, $format);

//affichage
echo _tag('time', $timeOptions, $dateFormat);