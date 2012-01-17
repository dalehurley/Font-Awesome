<?php
/*
 * _navigationWrapper.php
 * v0.2
 * Permet d'afficher une navigation de page (à améliorer avec gestion intégrée des tableaux
 * 
 * Variables disponibles :
 * $placement	top ou bottom	
 * $elements	liens de navigation à afficher (html)
 * $pager		pager de type diem (html)
 * $isLight		indique une version simplifiée
 * 
 */

//Définitions des valeurs par défaut

//permet de ne pas être obligé de définir cette variable lorsque égale à false
if(!isset($isLight)) $isLight = false;

$ctnTag = $isLight ? 'span.navigation' : 'div.navigation';

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
$html = _open($ctnTag . $ctnClass);

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

//fermeture container de la navigation
$html.= _close($ctnTag . $ctnClass);

//affichage html en sortie
echo $html;