<?php
/*
 * Organization.php
 * v1.0
 * http://schema.org/Organization
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

//ouverture du container
if(isset($container)) $html.= _open($container, $ctnOpts);

//Properties from Thing :
$thingOpt = array();
if(isset($node))		$thingOpt['node']			= $node;
if(isset($description)) $thingOpt['description']	= $description;
if(isset($image))		$thingOpt['image']			= $image;
if(isset($name))		$thingOpt['name']			= $name;
if(isset($url))			$thingOpt['url']			= $url;
$html.= get_partial('global/schema/Thing', $thingOpt);

//on implémente pas tout pour le moment, juste ce dont on a besoin
//on extrait les variables contenus dans adresse et on remplace celle éventuellement définies
if(isset($address)) extract($address, EXTR_OVERWRITE);

$addressOpt = array('container' => 'div.address itemprop="address"');
if(isset($node))			$addressOpt['node']				= $node;
							$addressOpt['name']				= false;
if(isset($addressLocality)) $addressOpt['addressLocality']	= $addressLocality;
if(isset($postalCode))		$addressOpt['imapostalCodee']	= $postalCode;
if(isset($streetAddress))	$addressOpt['streetAddress']	= $streetAddress;
$html.= get_partial('global/schema/Thing/Intangible/StructuredValue/ContactPoint/PostalAddress', $addressOpt);

//fermeture du container
if(isset($container)) $html.= _close($container);

//Affichage html de sortie
echo $html;