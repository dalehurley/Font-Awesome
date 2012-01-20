<?php
/*
 * Thing.php
 * v1.0
 * Permet d'afficher un élément de base
 * http://schema.org/Thing
 * 
 * Variables disponibles :
 * $node
 * $enableContainer
 * 
 * Properties from Thing :
 * 
 * $description
 * $image
 * $name
 * $url
 * 
 */

//on affecte les valeurs par défaut en fonction de la node passée en paramètre
if(isset($node)) {
	//si les valeurs ne sont pas explicitement définies on récupère la valeur dans la node, sauf si la valeur est débrayée par FALSE
	if(!isset($description)) {
		try { $description = $node->getResume(); }
		catch(Exception $e) { $description = null; }
	}
	if(!isset($image)) {
		try { $image = $node->getImage(); }
		catch(Exception $e) { $image = null; }
	}
	if(!isset($name)) {
		try { $name = $node->getTitle(); }
		catch(Exception $e) { $name = null; }
	}
	//on affecte pas l'url à une valeur car elle impliquerait la génération d'un lien imposée sinon
	if(!isset($url)) $url = null;
}

$html = '';