<?php
/*
 * Thing.php
 * v1.2
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

//Configuration par défaut

//container par défaut
if(!isset($container)) $container = 'span';
//séparateur par défaut
if(!isset($separator)) $separator = '&#160;:&#160;';
//ellipsis par défaut
if(!isset($ellipsis)) $ellipsis = '&#160;(...)';

//Composition de la sortie html
$html = '';

//raccourcissement de la valeur si une longueur est définie
if($value != null && isset($valueLength)) $value = stringTools::str_truncate($value, $valueLength, $ellipsis, true, true);

//définition des options du container
$ctnOpts = array();
if(isset($itemprop)) {
	$ctnOpts['class'][] = $itemprop;
	if((!isset($type) && !isset($url)) || $itemprop == 'url') $ctnOpts['itemprop'] = $itemprop;
}
if(isset($customClass)) $ctnOpts['class'][] = $customClass;

//concaténation du lien si présent
if(isset($url) && $itemprop != 'url') {
	$linkOpt = array();
	if(isset($itemprop)) $linkOpt['itemprop'] = $itemprop;
	$value = _link($url)->text($value)->set($linkOpt);
}

//si le type est définit on engloble le tout dans un container
if(isset($type)){
	$valueOpt = array();
	if(isset($itemprop) && !isset($url)) $valueOpt['itemprop'] = $itemprop;
	
	$html.= _open($container, $ctnOpts);
	
		$html.= _tag('span.type', $type);
		$html.= _tag('span.separator', $separator);
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