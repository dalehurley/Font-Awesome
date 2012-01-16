<?php
/*
 * _publicationShow.php
 * v0.2
 * Permet d'afficher une publication (article, actu de cabinet, etc)
 * 
 * Variables disponibles :
 * $node		élément englobant tous les objet de la page
 * $itemType	indique le type de node
 * $category
 * $section
 * $title
 * $image
 * $teaser
 * $content
 * 
 */

//Définitions des valeurs par défaut
if(!isset($itemType))	$itemType = 'Article';

//Déclaration du container contenant l'article
$ctn = ($itemType == 'Article') ? 'article' : 'div';
switch ($itemType) {
	case 'Article':
		$ctnOpts['itemscope'] = 'itemscope';
		$ctnOpts['itemtype'] = 'http://schema.org/Article';
		break;
	case 'Person' :
		$ctnOpts['itemscope'] = 'itemscope';
		$ctnOpts['itemtype'] = 'http://schema.org/Person';
	default:
		$ctnOpts = array();
		break;
}


//ouverture container de publication
echo _open($ctn, $ctnOpts);

	//header du contenu
	$headerOpts = array();
	if(isset($node))		$headerOpts['node']		= $node;
	if(isset($category))	$headerOpts['category']	= $category;
	if(isset($section))		$headerOpts['section']	= $section;
	if(isset($title))		$headerOpts['title']	= $title;
	if(isset($image))		$headerOpts['image']	= $image;
	if(isset($teaser))		$headerOpts['teaser']	= $teaser;
	include_partial('global/contentHeader', $headerOpts);
	
	//affichage du contenu de la page
	echo _tag('section.contentBody', array('itemprop' => 'articleBody'), $content);
	
//fermeture container de publication
echo _close($ctn);