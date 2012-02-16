<?php
/*
 * _partialInitValues.php
 * v1.0
 * Permet d'initialiser des variables du partial
 * 
 * Variables disponibles :
 * $separator
 * $dash
 * $ellipsis
 * $isListing			indique qu'il s'agit d'un listing
 * $isLight				permet d'indiquer une version allégée (notamment pour des affichages spéciaux dans les sidebars)
 * $noMicrodata			désactive tout ajout de microdata (désactivé par défaut)
 * $isDateMeta
 * 
 */

//récupération des différentes variables par défault
$separator =  _tag('span.separator', sfConfig::get('app_vars-partial_separator'));
$ellipsis = _tag('span.ellipsis', sfConfig::get('app_vars-partial_ellipsis'));
$dash = _tag('span.dash', sfConfig::get('app_vars-partial_dash'));

//longueur de texte de description par défaut
$defaultValueLength = sfConfig::get('app_vars-partial_defaultValueLength');

//permet de forcer la version listing de l'affichage
if(!isset($isListing)) $isListing = false;

//permet d'indiquer une version light de l'affichage (pour les listings simples)
if(!isset($isLight)) $isLight = false;

//active l'affichage de la date en balise meta et désactive l'affichage des dates
if(!isset($isDateMeta)) $isDateMeta = false;

//active l'ouverture du lien dans une nouvelle fenêtre
if(!isset($isUrlBlank)) $isUrlBlank = false;