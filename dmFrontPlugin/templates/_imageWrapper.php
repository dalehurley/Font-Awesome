<?php
/*
 * _imageWrapper.php
 * v0.1
 * Permet d'afficher une image complète.
 * 
 * Variables disponibles :		
 * $image		lien vers l'image à afficher
 * $alt			texte alternatif de l'image
 * $width		largeur de l'image
 * $height		hauteur de l'image
 * 
 */

//Définitions des valeurs par défaut
$html = '';

$html.= _open('div.imageWrapper');
	$html.= _media($image)
				->set('.image itemprop="image"')
				->alt($alt)
				//redimenssionnement propre lorsque l'image sera en bibliothèque
				->width($width)
				->height($height);
$html.= _close('div.imageWrapper');

//affichage html en sortie
echo $html;