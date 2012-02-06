<?php
/*
 * Thing.php
 * v1.1
 * http://schema.org/Thing
 * 
 * Variables disponibles :
 * $node
 * $container
 * $imageGridWidth		nombre de colonnes en largeur de l'image
 * $imageGridHeight		nombre de lignes en hauteur de l'image
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

//intégration de l'image
if($isImage) {
	//dimensions par défaut de l'image
	$imageGridWidth = (isset($imageGridWidth)) ? $imageGridWidth : sidSPLessCss::getLessParam('thumbS_col');
	$imageGridHeight = (isset($imageGridHeight)) ? $imageGridHeight : sidSPLessCss::getLessParam('thumbS_bl');
	
	//options de l'image
	$imageWrapperOpts = array(
								'image'	=>	$image,
								'width'	=>	spLessCss::gridGetWidth($imageGridWidth,0),
								'height'=>	spLessCss::gridGetHeight($imageGridHeight,0)
								);
	//ajout du nom de l'article dans la balise Alt de l'image
	if(isset($name)) $imageWrapperOpts['alt'] = $name;
	
	//Appel du partial d'image
	$html.= get_partial('global/schema/DataType/Image', $imageWrapperOpts);
}

if(isset($name)) if($name) $html.= get_partial('global/schema/DataType/Text', array('value' => $name, 'itemprop' => 'name'));
if(isset($description)) if($description) $html.= get_partial('global/schema/DataType/Text', array('value' => $description, 'itemprop' => 'description'));

//voir pour intégration image et url dans les datatypes

//englobage dans un container
if(isset($container)) $html = _tag($container, $ctnOpts, $html);

//Affichage html de sortie
echo $html;