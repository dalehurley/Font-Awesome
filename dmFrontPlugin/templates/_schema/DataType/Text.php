<?php
/*
 * Text.php
 * v1.4
 * Permet d'afficher un élément de base
 * 
 * Variables disponibles :
 * $type
 * $value
 * $valueLength
 * $itemprop
 * $customClass
 * $url
 * $container
 * $separator
 * 
 */

//récupération des valeurs par défaut
$includeDefaultValues = sfConfig::get('dm_front_dir') . '/templates/_schema/_varsDefaultValues.php';
include $includeDefaultValues;

//Configuration par défaut

//container par défaut
if(!isset($container)) $container = 'span';

//Composition de la sortie html
$html = '';

//raccourcissement de la valeur si une longueur est définie
if($value != null && isset($valueLength)) $value = stringTools::str_truncate($value, $valueLength, $ellipsis, true, true);

//définition des options du container
$ctnOpts = array();
if(isset($itemprop)) {
	$ctnOpts['class'][] = 'itemprop';
	$ctnOpts['class'][] = $itemprop;
	if((!isset($type) && !isset($url)) || $itemprop == 'url') $ctnOpts['itemprop'] = $itemprop;
}
if(isset($customClass)) $ctnOpts['class'][] = $customClass;

//concaténation du lien si présent
if(isset($url) && $itemprop != 'url') {
	$linkOpt = array();
	if(isset($itemprop)) $linkOpt['itemprop'] = $itemprop;
	
	//composition du lien html (permet d'afficher le type comme intitulé du lien si définit et version light)
	$htmlLink = _link($url)->set($linkOpt);
	if($isLight && isset($type))	$htmlLink->text($type);
	else							$htmlLink->text($value);
	
	//remplacement de la valeur par le lien
	$value = $htmlLink;
}

//si le type est définit on engloble le tout dans un container
if(isset($type)){
	$valueOpt = array();
	if(isset($itemprop) && !isset($url)) $valueOpt['itemprop'] = $itemprop;
	
	$html.= _open($container, $ctnOpts);
	
		$html.= _tag('span.type', array('title' => $type), $type);
		$html.= $separator;
		$html.= _tag('span.value', $valueOpt, $value);
	
	$html.= _close($container);
	
}else{
	if($itemprop == 'url') {
		//réattribution de la class link enlevée par le set
		$ctnOpts['class'][] = 'link';
		$html.= _link($url)->text($value)->set($ctnOpts);
	}
	else $html.= _tag($container, $ctnOpts, $value);
}

//affichage du html
echo $html;