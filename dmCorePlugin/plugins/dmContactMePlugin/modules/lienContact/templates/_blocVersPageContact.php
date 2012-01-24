<?php
// vars : $titreBloc, $titreLien, $message
$html = get_partial('global/titleWidget', array('title' => $titreBloc));

//texte de présentation
$html.= get_partial('global/contentWrapper', array('content' => $message));

//création d'un tableau de liens à afficher
$elements = array();
$elements[] = array('title' => $titreLien, 'linkUrl' => 'main/contact');
$html.= get_partial('global/navigationWrapper', array(
												'placement' => 'bottom',
												'elements' => $elements
												));

//affichage html en sortie
echo $html;