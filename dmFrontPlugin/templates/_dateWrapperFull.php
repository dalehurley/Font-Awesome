<?php
/*
 * _dateWrapperFull.php
 * v0.2
 * Permet d'afficher une date complète.
 * 
 * Variables disponibles :
 * $node			élément englobant tous les objet de la page
 * $dateCreated		date de création
 * $dateModified	date de mise à jour
 * 
 */
//Valeurs par défaut
if(isset($node) && isset($node->created_at)) $dateCreated = $node->created_at;
if(isset($node) && isset($node->updated_at)) $dateModified = $node->updated_at;

//html de sortie
$dateDisplay = "";

//ajout date de publication
if(isset($dateCreated)){
	$dateDisplay.= "Publié le ";
	$dateDisplay.= get_partial('global/dateTime', array(
												'date'	=>	$dateCreated,
												'type'	=>	'created',
												'format'=>	'D'
												));
}

//ajout date de mise à jour si présente et différent de la date de création
if(isset($dateCreated) && isset($dateModified)){
	
	//comparaison des dates au jour prêt
	$compCreated = intval(str_replace('-', '', format_date($dateCreated, 'i')));
	$compUpdated = intval(str_replace('-', '', format_date($dateModified, 'i')));
	
	//on ajoute la date de mise à jour que si elle est supérieure au niveau du jour
	if($compUpdated > $compCreated){
		$dateDisplay.= " | ";
		$dateDisplay.= "Mis à jour le ";
		$dateDisplay.= get_partial('global/dateTime', array(
												'date'	=>	$dateModified,
												'type'	=>	'updated',
												'format'=>	'D'
												));
	}
}

//affichage
echo _tag('span.date', array(), $dateDisplay);