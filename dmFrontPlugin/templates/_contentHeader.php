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

echo _open('header.contentHeader', array());
	
	//La rubrique l'article, à savoir Actualités, Chiffre, Dossier, etc
	if(isset($category)) echo _tag('h2.category', array('itemprop' => 'articleSection'), $category);
	
	//La section de l'article, à savoir Social, Juridique, Fiscal, etc
	if(isset($section)) echo _tag('h3.section', array('itemprop' => 'articleSection'), $section);
	
	//Le titre de l'article, devant toujours être l'unique H1 dans la page
	if(isset($title)) echo _tag('h1.title', array('itemprop' => 'name'), $title);
	
	if(isset($image)){
		include_partial('global/imageWrapperFull', array(
													'image'	=>	$image,
													'alt'	=>	$title,
													'width'	=>	spLessCss::gridGetContentWidth(),
													'height'=>	spLessCss::gridGetHeight(14,0)
													));
	}
	
	//Chapeau de l'article si présent (filtrer tout balisage html, texte brut, sauf sup et sub)
	if(isset($teaser)) include_partial('global/teaserWrapper', array('teaser' => $teaser));
	
	//Gestion de la date avec plusieurs possibilités (dateCreated, dateModified, etc)
	if(isset($node->created_at)) include_partial('global/dateWrapperFull', array('node' => $node));
	
echo _close('header');