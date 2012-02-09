<?php
/*
 * Organization.php
 * v1.2
 * http://schema.org/Organization
 * 
 * Variables disponibles :
 * $container
 * 
 * Properties from Thing :
 * $description
 * $image
 * $name
 * $url
 * 
 * Properties from Organization :
 * address
 * aggregateRating
 * contactPoints
 * email
 * mployees
 * events
 * faxNumber
 * founders
 * foundingDate
 * interactionCount
 * location
 * members
 * reviews
 * telephone
 * 
 */
//Définition du type (inséré dans le container si présent)
$itemType = "Organization";

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

//ajout du nom et de la description
if(isset($name)) $html.= get_partial('global/schema/DataType/Text', array('value' => $name, 'itemprop' => 'name'));
if(isset($description)) $html.= get_partial('global/schema/DataType/Text', array('value' => $description, 'itemprop' => 'description'));

//on implémente pas tout pour le moment, juste ce dont on a besoin
//on extrait les variables contenus dans adresse et on remplace celle éventuellement définies
if(isset($address)) extract($address, EXTR_OVERWRITE);

$addressOpt = array('container' => 'div.address itemprop="address"', 'url' => false);
//désactivation d'éléments
$addressOpt['name']				= false;
$addressOpt['email']			= false;
$addressOpt['faxNumber']		= false;
$addressOpt['telephone']		= false;

//ajout des propriétés			
if(isset($addressLocality)) $addressOpt['addressLocality']	= $addressLocality;
if(isset($postalCode))		$addressOpt['postalCode']		= $postalCode;
if(isset($streetAddress))	$addressOpt['streetAddress']	= $streetAddress;
$html.= get_partial('global/schema/Thing/Intangible/StructuredValue/ContactPoint/PostalAddress', $addressOpt);

//ajout d'élément exteralisés
if(isset($email))		$html.= get_partial('global/schema/DataType/Text', array('type' => __('Email'),			'value' => $email,			'itemprop' => 'email', 'url' => 'mailto:' . $email));
if(isset($telephone))	$html.= get_partial('global/schema/DataType/Text', array('type' => __('Phone'),			'value' => $telephone,		'itemprop' => 'telephone'));
if(isset($faxNumber))	$html.= get_partial('global/schema/DataType/Text', array('type' => __('Fax'),			'value' => $faxNumber,		'itemprop' => 'faxNumber'));

//englobage dans un container
if(isset($container)) $html = _tag($container, $ctnOpts, $html);

//Affichage html de sortie
echo $html;