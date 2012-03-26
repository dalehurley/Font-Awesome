<?php
$html = '';

//ouverture du listing
$html.= _open('ul.elements');

//compteur
$count = 0;
$maxCount = count($logos);

foreach ($logos as $logoName => $logoLink) {
	//incrémentation compteur
	$count++;
	
	//options de l'élément
	$elementOpt = array();

	//gestion de l'index de positionnement
	if($count == 1)			$elementOpt['class'][] = 'first';
	if($count >= $maxCount)	$elementOpt['class'][] = 'last';
	
	//création du lien
	if ($logoLink != ''){
		$htmlLink = _link($logoLink)->text($logoName)->title(__('Follow us on') . ' ' . $logoName)->set('.link_' . dmString::slugify($logoName))->target('blank');
		//insertion du lien dans un li
		$html.= _tag('li.element', $elementOpt, $htmlLink);
	} 

}

//fermeture du listing
$html.= _close('ul.elements');

//affichage html en sortie
echo $html;