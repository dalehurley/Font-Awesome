<?php
/*
 * _publicationShow.php
 * v0.3
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
$html = '';

//Définitions des valeurs par défaut
if(!isset($itemType))	$itemType = 'Article';

//on affecte les valeurs par défaut en fonction de la node passée en paramètre
if(isset($node)) {
	//si les valeurs ne sont pas explicitement définies on récupère la valeur dans la node
	if(!isset($title)) {
		try { $title = $node->getTitle(); }
		catch(Exception $e) { $title = null; }
	}
	if(!isset($image)) {
		try { $image = $node->getImage(); }
		catch(Exception $e) { $image = null; }
	}
	if(!isset($content)) {
		try { $content = $node->getText(); }
		catch(Exception $e) { $content = null; }
	}
}

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
$html.= _open($ctn, $ctnOpts);

	//header du contenu
	$headerOpts = array();
	if(isset($node))		$headerOpts['node']		= $node;
	if(isset($category))	$headerOpts['category']	= $category;
	if(isset($section))		$headerOpts['section']	= $section;
	if(isset($title))		$headerOpts['title']	= $title;
	if(isset($image))		$headerOpts['image']	= $image;
	if(isset($teaser))		$headerOpts['teaser']	= $teaser;
	$html.= get_partial('global/contentHeader', $headerOpts);
	
	//affichage du contenu de la page
	$html.= _tag('section.contentBody', array('itemprop' => 'articleBody'), $content);
	
//fermeture container de publication
$html.= _close($ctn);

//affichage html de sortie
echo $html;