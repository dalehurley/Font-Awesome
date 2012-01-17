<?php
$html = '';

// si nous avons des actu articles
if (count($missions)) {
	
	//titre du contenu
	$html.= get_partial('global/titleWidget', array('title' => $titreBloc, 'isContainer' => true));
	
	/*
	foreach ($missions as $mission) {
		//ouverture du container
		$html.= _open('div.supWrapper');
		
		//fermeture du container
		$html.= _close('div.supWrapper');
	}*/
	
	//affichage des missions
	$pubOpts = array();
	$pubOpts['node']		= $missions;

	$html.= get_partial('global/publicationShow', $pubOpts);
	
} // sinon on affiche rien

//affichage html de sortie
echo $html;