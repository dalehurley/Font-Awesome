<?php
/*
 * _imageWrapperFull.php
 * v0.2
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

//on vérifie que l'image existe sur le serveur avec son chemin absolu
$isImage = false;
if(isset($image)) {
	//on vérifie que l'image existe sur le serveur avec son chemin absolu
	$imageUpload = (strpos($image, 'uploads') === false) ? '/uploads/' : '/';
	$isImage = is_file(sfConfig::get('sf_web_dir') . $imageUpload . $image);
}

if ($isImage) {
	$html.= _open('div.imageFullWrapper');
		$html.= _media($image)
					->set('.image itemprop="image"')
					->alt($alt)
					//redimenssionnement propre lorsque l'image sera en bibliothèque
					->width($width)
					->height($height);
	$html.= _close('div.imageFullWrapper');
}

//affichage html en sortie
echo $html;