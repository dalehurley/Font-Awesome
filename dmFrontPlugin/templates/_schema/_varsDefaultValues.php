<?php
/*
 * _varsDefaultValues.php
 * v1.1
 * Permet de définir de façon centralisée des valeurs par défaut pour différentes variables
 * 
 * Variables disponibles :
 * $separator
 * $dash
 * $ellipsis
 * $isListing			indique qu'il s'agit d'un listing
 * $isLight				permet d'indiquer une version allégée (notamment pour des affichages spéciaux dans les sidebars)
 * $noMicrodata			désactive tout ajout de microdata (désactivé par défaut)
 * 
 */

//séparateur par défaut
if(!isset($separator)) $separator = '&#160;:&#160;';
$separator = _tag('span.separator', $separator);

//tiret par défaut
if(!isset($dash)) $dash = '&#160;-&#160;';
$dash = _tag('span.dash', $dash);

//ellipsis par défaut
if(!isset($ellipsis)) $ellipsis = '&#160;(...)';
$ellipsis = _tag('span.ellipsis', $ellipsis);

//permet de forcer la version listing de l'affichage
if(!isset($isListing)) $isListing = false;

//permet d'indiquer une version light de l'affichage (pour les listings simples)
if(!isset($isLight)) $isLight = false;

//désactive tout ajout de microdata
if(!isset($noMicrodata)) $noMicrodata = false;

//longueur de texte de description par défaut
$defaultValueLength = 200;