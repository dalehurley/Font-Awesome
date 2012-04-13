<?php
// vars : $titreBloc, $lien, $message, $href
$html = get_partial('global/titleWidget', array('title' => $titreBloc));

//texte de présentation
$html.= _tag('div.wrapper', $message);

//création d'un tableau de liens à afficher
$elements = array();
$elements[] = array('title' => $lien, 'linkUrl' => $href);
$html.= get_partial('global/navigationWrapper', array(
												'placement' => 'bottom',
												'elements' => $elements
												));

//affichage html en sortie
echo $html;