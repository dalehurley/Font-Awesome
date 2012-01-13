<?php
/*
 * _publicationShow.php
 * v0.1
 * Permet d'afficher une publication (article, actu de cabinet, etc)
 * 
 * Variables disponibles :
 * $node		élément englobant tous les objet de la page
 * $category
 * $section
 * $title
 * $image
 * $teaser
 * $content
 * 
 * Pour info : http://blog.rajatpandit.com/2009/01/28/datehelper-date_format-in-symfony/
 */

//ouverture container de publication
echo _open('article', array(
						'itemscope'	=>	'itemscope',
						'itemtype'	=>	'http://schema.org/Article'
						));

	//header du contenu
	$headerOpts = array();
	if(isset($node))		$headerOpts['node']		= $node;
	if(isset($category))	$headerOpts['category']	= $category;
	if(isset($section))		$headerOpts['section']	= $section;
	if(isset($title))		$headerOpts['title']		= $title;
	if(isset($image))		$headerOpts['image']		= $image;
	if(isset($teaser))		$headerOpts['teaser']	= $teaser;
	include_partial('global/contentHeader', $headerOpts);
	
	//affichage du contenu de la page
	echo _tag('section.contentBody', array('itemprop' => 'articleBody'), $content);
	
//fermeture container de publication
echo _close('article');