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
$html.= _close('div.navigation' . $ctnClass);

//affichage html en sortie
echo $html;