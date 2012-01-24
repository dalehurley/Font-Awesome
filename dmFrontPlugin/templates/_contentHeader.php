<?php
/*
 * _contentHeader.php
 * v0.1
 * Permet d'afficher un header de contenu
 * 
 * Variables disponibles :
 * $node
 * $category
 * $section
 * $title
 * $image
 * $teaser
 * 
 */

$html = _open('header.contentHeader', array());
	
	//La rubrique l'article, à savoir Actualités, Chiffre, Dossier, etc
	if(isset($category)) $html.= _tag('h2.category', array('itemprop' => 'articleSection'), $category);
	
	//La section de l'article, à savoir Social, Juridique, Fiscal, etc
	if(isset($section)) $html.= _tag('h3.section', array('itemprop' => 'articleSection'), $section);
	
	if(isset($image)){
		$html.= get_partial('global/imageWrapperFull', array(
													'image'	=>	$image,
													'alt'	=>	$title,
													'width'	=>	spLessCss::gridGetContentWidth(),
													'height'=>	spLessCss::gridGetHeight(14,0)
													));
	}
	
	//Le titre de l'article, devant toujours être l'unique H1 dans la page
	if(isset($title)) $html.= _tag('h1.title', array('itemprop' => 'name'), $title);
	
	//Chapeau de l'article si présent
	if(isset($teaser)) $html.= get_partial('global/teaserWrapper', array('teaser' => $teaser));
	
	//Gestion de la date avec plusieurs possibilités (dateCreated, dateModified, etc)
	if(isset($node->created_at)) $html.= get_partial('global/dateWrapperFull', array('node' => $node));
	
$html.= _close('header');

//affichage html en sortie
echo $html;