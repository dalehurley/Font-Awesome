<?php
/*
 * Thing.php
 * v1.1
 * http://schema.org/Thing
 * 
 * Variables disponibles :
 * $node
 * $container
 * $imageGridWidth
 * $imageGridHeight
 * $imageContainer
 * 
 * Properties from Thing :
 * $description
 * $image
 * $name
 * $url
 * 
 */
//Définition du type (inséré dans le container si présent)
$itemType = "ContactPoint";

//récupération des valeurs dans la node par les getters par défaut
$includeDefault = sfConfig::get('dm_front_dir') . '/templates/_schema/_varsDefaultGetters.php';
include $includeDefault;

//Composition du html de sortie
$html = '';

//ouverture du container
if(isset($container)) $html.= _open($container, $ctnOpts);

//intégration de l'image
if(isset($image)) if($image) {
	//dimensions par défaut de l'image
	$imageGridWidth = (isset($imageGridWidth)) ? $imageGridWidth : spLessCss::getLessParam('thumbS_col');
	$imageGridHeight = (isset($imageGridHeight)) ? $imageGridHeight : spLessCss::getLessParam('thumbS_bl');
	
	//en attendant meilleure intégration avec datatype image
	$html.= get_partial('global/imageWrapper', array(
													'image'	=>	$image,
													'alt'	=>	$title,
													'width'	=>	spLessCss::gridGetWidth($imageGridWidth,0),
													'height'=>	spLessCss::gridGetHeight($imageGridHeight,0)
													));
}

if(isset($name)) if($name) $html.= get_partial('global/schema/DataType/Text', array('value' => $name, 'itemprop' => 'name'));
if(isset($description)) if($description) $html.= get_partial('global/schema/DataType/Text', array('value' => $description, 'itemprop' => 'description'));

//voir pour intégration image et url dans les datatypes

//fermeture du container
if(isset($container)) $html.= _close($container);

//Affichage html de sortie
echo $html;