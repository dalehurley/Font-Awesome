<?php
/*
 * PostalAddress.php
 * v1.0
 * http://schema.org/PostalAddress
 * 
 * Variables disponibles :
 * $node
 * $container
 * 
 * Properties from Thing :
 * $description
 * $image
 * $name
 * $url
 * 
 * Properties from ContactPoint :
 * $contactType
 * $email
 * $faxNumber
 * $telephone
 * 
 * Properties from PostalAddress :
 * $addressCountry
 * $addressLocality
 * $addressRegion
 * $postOfficeBoxNumber
 * $postalCode
 * $streetAddress
 * 
 */
//Définition du type (inséré dans le container si présent)
$itemType = "PostalAddress";

//récupération des valeurs dans la node par les getters par défaut
$includeDefault = sfConfig::get('dm_front_dir') . '/templates/_schema/_varsDefaultGetters.php';
include $includeDefault;

//Composition du html de sortie
$html = '';

//Properties from Thing :
$thingOpt = array();
if(isset($node))		$thingOpt['node']			= $node;
if(isset($description)) $thingOpt['description']	= $description;
if(isset($image))		$thingOpt['image']			= $image;
if(isset($name))		$thingOpt['name']			= $name;
if(isset($url))			$thingOpt['url']			= $url;
$html.= get_partial('global/schema/Thing', $thingOpt);

//Properties from ContactPoint :
$contactPointOpt = array();
if(isset($contactType))	$contactPointOpt['contactType']	= $contactType;
if(isset($email))		$contactPointOpt['email']		= $email;
if(isset($faxNumber))	$contactPointOpt['faxNumber']	= $faxNumber;
if(isset($telephone))	$contactPointOpt['telephone']	= $telephone;
$html.= get_partial('global/schema/Thing/Intangible/StructuredValue/ContactPoint', $contactPointOpt);

//Properties from PostalAddress :
if(isset($streetAddress))	if($streetAddress)	$html.= get_partial('global/schema/DataType/Text', array('type' => __('Street'),		'value' => $streetAddress,	'itemprop' => 'streetAddress'));
if(isset($postalCode))		if($postalCode)		$html.= get_partial('global/schema/DataType/Text', array('type' => __('Postal Code'),	'value' => $postalCode,		'itemprop' => 'postalCode'));
if(isset($addressLocality))	if($addressLocality)$html.= get_partial('global/schema/DataType/Text', array('type' => __('Locality'),		'value' => $addressLocality,'itemprop' => 'addressLocality'));
if(isset($addressRegion))	if($addressRegion)	$html.= get_partial('global/schema/DataType/Text', array('type' => __('Region'),		'value' => $addressRegion,	'itemprop' => 'addressRegion'));
if(isset($addressCountry))	if($addressCountry)	$html.= get_partial('global/schema/DataType/Text', array('type' => __('Country'),		'value' => $addressCountry,	'itemprop' => 'addressCountry'));

//englobage dans un container
if(isset($container)) $html = _tag($container, $ctnOpts, $html);

//Affichage html de sortie
echo $html;