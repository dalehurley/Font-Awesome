<?php
/*
 * Image.php
 * v1.0
 * Permet d'afficher une image
 * 
 * Variables disponibles :
 * $image
 * $alt
 * $width		largeur de l'image
 * $height		hauteur de l'image
 * $container	container de l'image
 * 
 */

//Voir pour intégration base64 pour image non stockée en base

//Configuration par défaut

//container par défaut
if(!isset($container)) $container = 'span.imageWrapper';

//Composition de la sortie html
$html = _media($image)
			->set('.image itemprop="image"')
			->alt($alt)
			->width($width)
			->height($height);

//Ajout des options à l'image
if(isset($alt))		$html->alt($alt);
if(isset($width))	$html->width($width);
if(isset($height))	$html->height($height);

//englobage dans le container
$html = _tag($container, $html);

//affichage html en sortie
echo $html;