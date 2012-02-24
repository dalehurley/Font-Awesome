<?php
/*
 * _partialInitImage.php
 * v1.0
 * Permet d'initialiser l'image du partial
 * 
 * Variables disponibles :
 * $image
 * 
 */

//définition de l'image
$isImage = false;
if(isset($image)) {
	//on vérifie si l'image est en base (de type objet dans ce cas)
	$isDbImage = (gettype($image) == 'object') ? true : false;
	
	//si l'image est en base alors on rajoute le dossier upload devant pour obtenir l'url absolue
	//$imageUpload = ($isDbImage) ? sfConfig::get('sf_upload_dir') . '/' : sfConfig::get('sf_web_dir') . '/';
	$imageUpload = ($isDbImage) ? sfConfig::get('sf_upload_dir') . '/' : sfConfig::get('sf_web_dir') . '/';
	
	//composition url absolue de l'image
	$imageUrl = $imageUpload . $image;
	
	//test de la présence de l'image
	$isImage = is_file($imageUrl);
}