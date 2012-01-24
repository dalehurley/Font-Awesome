<?php
/*
 * _dateWrapperShort.php
 * v0.1
 * Permet d'afficher une date complète.
 * 
 * Variables disponibles :
 * $node		élément englobant tous les objet de la page
 * 
 */

$dateDisplay = "";

//ajout date de publication
if(isset($node->created_at)){
	$dateDisplay.= "(";
	$dateDisplay.= get_partial('global/dateTime', array(
												'date'	=>	$node->created_at,
												'type'	=>	'created',
												'format'=>	'd'
												));
	$dateDisplay.= ")";
}

//affichage
echo _tag('span.date', array(), $dateDisplay);