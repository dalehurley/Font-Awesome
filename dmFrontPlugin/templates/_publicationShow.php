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
	include_partial('global/contentHeader', array(
												'node'		=>	$node,
												'category'	=>	$category,
												'section'	=>	$section,
												'title'		=>	$title,
												'image'		=>	$image,
												'teaser'	=>	$teaser
											));
	
	//affichage du contenu de la page
	echo _tag('section.contentBody', array('itemprop' => 'articleBody'), $content);
	
//fermeture container de publication
echo _close('article');