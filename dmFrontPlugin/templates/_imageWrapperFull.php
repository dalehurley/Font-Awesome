<?php
/*
 * _imageWrapperFull.php
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

//on vérifie que l'image existe sur le serveur avec son chemin absolu
$imgExist = is_file(sfConfig::get('sf_web_dir') . $image);

if ($imgExist) {
	echo _open('div.imageFullWrapper');
		echo _media($image)
					->set('.image itemprop="image"')
					->alt($alt)
					//redimenssionnement propre lorsque l'image sera en bibliothèque
					->width($width)
					->height($height);
	echo _close('div');
}