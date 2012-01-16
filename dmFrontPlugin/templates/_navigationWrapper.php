<?php
/*
 * _navigationWrapper.php
 * v0.1
 * Permet d'afficher une navigation de page (à améliorer avec gestion intégrée des tableaux
 * 
 * Variables disponibles :
 * $placement	top ou bottom	
 * $elements	liens de navigation à afficher (html)
 * $pager		pager de type diem (html)
 * 
 */

//Définitions des valeurs par défaut
switch ($placement) {
	case 'top':
		$ctnClass = '.navigationTop';
		break;
	case 'bottom':
		$ctnClass = '.navigationBottom';
		break;
	default:
		$ctnClass = '';
		break;
}

//ouverture container de la navigation
$html = _open('div.navigation' . $ctnClass);

	//liens de navigation multiples
	if(isset($elements)){
		$html.= _open('ul.elements');
			$html.= $elements;
		$html.= _close('ul.elements');
	}
	
	//Pager par défaut de Diem de type 1,2,3,4,5
	if(isset($pager)){
		$html.= _open('ul.pager');
			$html.= $pager;
		$html.= _close('ul.pager');
	}

//ouverture container de la navigation
$html.= _close('div.navigation' . $ctnClass);