<?php
/*
 * _dateWrapperShort.php
 * v0.2
 * Permet d'afficher une date complète.
 * 
 * Variables disponibles :
 * $node		élément englobant tous les objet de la page
 * $dateCreated	date de création
 * 
 */
//Valeurs par défaut
if(isset($node) && isset($node->created_at)) $dateCreated = $node->created_at;

//html de sortie
$dateDisplay = "";

//ajout date de publication
if($dateCreated){
	$dateDisplay.= "(";
	$dateDisplay.= get_partial('global/dateTime', array(
												'date'	=>	$dateCreated,
												'type'	=>	'created',
												'format'=>	'd'
												));
	$dateDisplay.= ")";
}

//affichage
echo _tag('span.date', array(), $dateDisplay);