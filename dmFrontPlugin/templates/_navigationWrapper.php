<?php
/*
 * _navigationWrapper.php
 * v0.9
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
		//affichage du li contenant le lien
		$html.= get_partial('global/publicationListElementLight', array(
												'title' => $element['title'],
												'linkUrl' => $element['linkUrl'],
												'count' => $count,
												'maxCount' => $maxCount
												));
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