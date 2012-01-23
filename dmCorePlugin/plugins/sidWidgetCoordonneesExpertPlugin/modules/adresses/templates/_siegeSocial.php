<?php
// vars : $adresse, $titreBloc
$html = '';

if (count($adresse)) {
	
	if($titreBloc != null) $html.= get_partial('global/titleWidget', array('title' => $titreBloc));
	
	$html.= get_partial('global/schema/Thing/Organization', array('node' => $adresse, 'container' => 'div.supWrapper'));
} // sinon on affiche rien

//affichage html de sortie
echo $html;