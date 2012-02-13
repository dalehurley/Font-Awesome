<?php
/*
 * _dateWrapperFull.php
 * v0.3
 * Permet d'afficher une date complète.
 * 
 * Variables disponibles :
 * $node			élément englobant tous les objet de la page
 * $dateCreated		date de création
 * $dateModified	date de mise à jour
 * $isFooter			indique une version longue
 * 
 */
//Valeurs par défaut
if(isset($node) && isset($node->created_at)) $dateCreated = $node->created_at;
if(isset($node) && isset($node->updated_at)) $dateModified = $node->updated_at;
if(!isset($isFooter)) $isFooter = false;

//html de sortie
$dateDisplay = "";

//ajout date de publication
if(isset($dateCreated)){
	$dateDisplay.= $isFooter ? "Article du " : "Publié le ";
	$dateDisplay.= get_partial('global/dateTime', array(
												'date'	=>	$dateCreated,
												'type'	=>	'created',
												'format'=>	$isFooter ? 'd' : 'D'
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
												'format'=>	$isFooter ? 'd' : 'D'
												));
	}
}

//affichage
echo _tag('span.date', array(), $dateDisplay);