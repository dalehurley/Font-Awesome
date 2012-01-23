<?php
/*
 * _dateWrapperFull.php
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
	$dateDisplay.= "Publié le ";
	$dateDisplay.= get_partial('global/dateTime', array(
												'date'	=>	$node->created_at,
												'type'	=>	'created',
												'format'=>	'D'
												));
}

//ajout date de mise à jour si présente et différent de la date de création
if(isset($node->created_at) && isset($node->updated_at)){
	
	//comparaison des dates au jour prêt
	$compCreated = intval(str_replace('-', '', format_date($node->created_at, 'i')));
	$compUpdated = intval(str_replace('-', '', format_date($node->updated_at, 'i')));
	
	//on ajoute la date de mise à jour que si elle est supérieure au niveau du jour
	if($compUpdated > $compCreated){
		$dateDisplay.= " | ";
		$dateDisplay.= "Mis à jour le ";
		$dateDisplay.= get_partial('global/dateTime', array(
												'date'	=>	$node->updated_at,
												'type'	=>	'updated',
												'format'=>	'D'
												));
	}
}

//affichage
echo _tag('span.date', array(), $dateDisplay);