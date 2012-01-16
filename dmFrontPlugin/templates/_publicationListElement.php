<?php
/*
 * _publicationListElement.php
 * v0.1
 * Permet d'afficher une publication de façon simplifié à l'intérieur d'une liste ul li
 * 
 * Variables disponibles :
 * $node		élément englobant tous les objet de la page
 * $itemType	indique le type de node
 * $isLink		indique si il s'agit d'un lien ou non
 * $category
 * $section
 * $title
 * $image
 * $teaser
 * 
 */

//Définitions des valeurs par défaut
if(!isset($isLink))	$isLink = false;
