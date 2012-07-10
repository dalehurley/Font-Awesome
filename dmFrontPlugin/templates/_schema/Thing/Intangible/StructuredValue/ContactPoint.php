<?php
/*
 * ContactPoint.php
 * v1.2
 * http://schema.org/ContactPoint
 * 
 * Variables disponibles :
 * $container
 * $isLight
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
 */
//Définition du type (inséré dans le container si présent)
$itemType = "ContactPoint";

//insertions des includes nécessaires à ce partial
$initContainer = sfConfig::get('dm_front_dir') . '/templates/_schema/_partialInitContainer.php';
include $initContainer;


//Composition du html de sortie
$html = '';

//Properties from Thing :
$thingOpt = array();
if(isset($description)) $thingOpt['description']	= $description;
if(isset($image))		$thingOpt['image']			= $image;
if(isset($name))		$thingOpt['name']			= $name;
if(isset($url))			$thingOpt['url']			= $url;
$html.= get_partial('global/schema/Thing', $thingOpt);

//Properties from ContactPoint :
if(isset($contactType) && $contactType != null)	$html.= get_partial('global/schema/DataType/Text', array('type' => __('Contact type'),	'value' => $contactType,	'itemprop' => 'contactType'));

//options d'affichage de l'email
if(isset($email) && $email != null) {
	$emailOpt = array(
				'type'		=> __('Email'),
				'value'		=> $email,
				'itemprop'	=> 'email',
				'isLight'	=> $isLight
			);
	//si l'URL n'est pas définit pour la personne ou désactivé alors on peut rajouter l'email dans l'affichage
	if(!isset($url) || (isset($url) && $url == null)) $emailOpt['url'] = 'mailto:' . $email;
		
	$html.= get_partial('global/schema/DataType/Text', $emailOpt);
}

if(isset($telephone) && $telephone != null)	$html.= get_partial('global/schema/DataType/Text', array('type' => __('Phone'),			'value' => $telephone,		'itemprop' => 'telephone'));
if(isset($faxNumber) && $faxNumber != null)	$html.= get_partial('global/schema/DataType/Text', array('type' => __('Fax'),			'value' => $faxNumber,		'itemprop' => 'faxNumber'));

//englobage dans un container
if(isset($container)) $html = _tag($container, $ctnOpts, $html);

//Affichage html de sortie
echo $html;