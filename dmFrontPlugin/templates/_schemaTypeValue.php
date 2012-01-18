<?php
/*
 * _schemaTypeValue.php
 * v0.1
 * Permet d'afficher un numéro de téléphone
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

$valueCtn = 'span.value';
if(!$noProp) $valueCtn.= ' itemprop="' . $itemType . '"';

$html = _open($ctn);
	$html.= _tag('span.type', $type);
	$html.= '&#160;:&#160;';
	$html.= _tag($valueCtn, $value);
$html.= _close($ctn);

//affichage html en sortie
echo $html;