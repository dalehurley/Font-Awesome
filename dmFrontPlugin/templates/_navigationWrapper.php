<?php
/*
 * _navigationWrapper.php
 * v1.0
 * Permet d'afficher une navigation de page (à améliorer avec gestion intégrée des tableaux
 * 
 * Variables disponibles :
 * $placement	top ou bottom	
 * $elements	liens de navigation à afficher (html)
 * $pager		pager de type diem (html)
 * $container	container de l'ensemble
 * 
 */

//Configuration par défaut

//récupérations des options de page
$pageOptions = spLessCss::pageTemplateGetOptions();
$isDev = $pageOptions['isDev'];

//container par défaut
if(!isset($container)) $container = 'div';

//déclaration des propriétés par défaut du container
$ctnOpts = array('class' => array('navigation'));
switch ($placement) {
	case 'top':
		$ctnOpts['class'][] = 'navigationTop';
		break;
	case 'bottom':
		$ctnOpts['class'][] = 'navigationBottom';
		break;
	default:
		break;
}

//Composition de la sortie html
$html = '';

//liens de navigation multiples
if(isset($elements)){
	$html.= _open('ul.elements');

	//compteur
	$count = 0;
	$maxCount = count($elements);

	foreach ($elements as $element) {
		//incrémentation compteur
		$count++;
		
		//options de l'élément
		$elementOpt = array();
		
		//gestion de l'index de positionnement
		if($count == 1)			$elementOpt['class'][] = 'first';
		if($count >= $maxCount)	$elementOpt['class'][] = 'last';
		//application classe de debug
		if($isDev) $elementOpt['class'][] = 'isVerified';
		
		//insertion du lien dans un li
		$html.= _tag('li.element', $elementOpt, _link($element['linkUrl'])->text($element['title'])->title($element['title']));
	}

	$html.= _close('ul.elements');
}

//Pager par défaut de Diem de type 1,2,3,4,5
if(isset($pager)){
	$html.= _open('ul.pager');
		$html.= $pager;
	$html.= _close('ul.pager');
}

//englobage dans un container
$html = _tag($container, $ctnOpts, $html);

//affichage html en sortie
echo $html;