<?php
/*
 * _publicationListElementLight.php
 * v0.1
 * Permet d'afficher une publication de façon simplifié à l'intérieur d'une liste ul li
 * 
 * Variables disponibles :
 * $title		texte du lien
 * $linkUrl		indique si il s'agit d'un lien ou non
 * $count		indique le numéro de listing
 * $maxCount	indique le nombre maximal d'éléments affichages
 * 
 */
$html = '';
//Définitions des valeurs par défaut

//gestion de l'index de positionnement
$posClass = '';
if(isset($count) && isset($maxCount)) {
	if($count == 1)				$posClass = '.first';
	elseif($count >= $maxCount)	$posClass = '.last';
}

//ouverture container de publication
$html.= _open('li.element' . $posClass, array());
	//inclusion dans le lien si nécessaire
	if(isset($title) && isset($linkUrl)) $html.= _link($linkUrl)->text($title)->title($title);
//fermeture container de publication
$html.= _close('li.element');

//affichage html en sortie
echo $html;