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

//récupération des valeurs dans la node par les getters par défaut
$includeDefault = sfConfig::get('dm_front_dir') . '/templates/_schema/_varsDefaultGetters.php';
include $includeDefault;

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
if(isset($contactType))	$html.= get_partial('global/schema/DataType/Text', array('type' => __('Contact type'),	'value' => $contactType,	'itemprop' => 'contactType'));

//options d'affichage de l'email
if(isset($email)) {
	$emailOpt = array(
				'type'		=> __('Email'),
				'value'		=> $email,
				'itemprop'	=> 'email',
				'isLight'	=> $isLight
			);
	//si l'URL n'est pas définit pour la personne ou désactivé alors on peut rajouter l'email dans l'affichage
	if(!isset($url))					$emailOpt['url'] = 'mailto:' . $email;
	if(isset($url)) if($url == null)	$emailOpt['url'] = 'mailto:' . $email;
		
	$html.= get_partial('global/schema/DataType/Text', $emailOpt);
}

if(isset($telephone))	$html.= get_partial('global/schema/DataType/Text', array('type' => __('Phone'),			'value' => $telephone,		'itemprop' => 'telephone'));
if(isset($faxNumber))	$html.= get_partial('global/schema/DataType/Text', array('type' => __('Fax'),			'value' => $faxNumber,		'itemprop' => 'faxNumber'));

//englobage dans un container
if(isset($container)) $html = _tag($container, $ctnOpts, $html);

//Affichage html de sortie
echo $html;