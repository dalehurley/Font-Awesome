<?php
/*
 * Thing.php
 * v1.0
 * http://schema.org/Thing
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

if(isset($name)) if($name) $html.= get_partial('global/schema/DataType/Text', array('value' => $name, 'itemprop' => 'name'));
if(isset($description)) if($description) $html.= get_partial('global/schema/DataType/Text', array('value' => $description, 'itemprop' => 'description'));

//voir pour intégration image et url dans les datatypes

//fermeture du container
if(isset($container)) $html.= _close($container);

//Affichage html de sortie
echo $html;