<?php
/*
 * _schemaTypeValue.php
 * v0.2
 * Permet d'afficher un itemprop avec type value
 * 
 * Variables disponibles :
 * $itemType
 * $type
 * $value
 * $linkUrl
 * $isDiv
 * $noProp
 * 
 */

//permet de ne pas être obligé de définir cette variable lorsque égale à false
if(!isset($isDiv)) $isDiv = false;
if(!isset($noProp)) $noProp = false;


//définition du container
$ctn = $isDiv ? 'div.' : 'span.';
$ctn.= $itemType;

//définition du container contenant la valeur
$valueCtn = 'span.value';
//on affiche la propriété sur le span.value que si il n'y a pas de lien
if(!$noProp && !isset($linkUrl)) $valueCtn.= ' itemprop="' . $itemType . '"';

//on entoure la valeur insérée avec un lien si présent
if(isset($linkUrl)) {
	$value = _link($linkUrl)->text($value);
	if(!$noProp) $value->set('.link itemprop="email"');
}

$html = _open($ctn);
	$html.= _tag('span.type', $type);
	$html.= '&#160;:&#160;';
	$html.= _tag($valueCtn, $value);
$html.= _close($ctn);

//affichage html en sortie
echo $html;