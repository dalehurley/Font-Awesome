<?php
// vars : $missions, $titreBloc

//titre du contenu
$html = get_partial('global/titleWidget', array('title' => $titreBloc, 'isContainer' => true));

// si nous avons des actu articles
if(count($missions)) {
	//affichage du contenu
	$missionOpts = array('container' => 'article');
	$missionOpts['node'] = $missions;
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', $missionOpts);
	
}else{
	// sinon on affiche rien
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', array('articleBody' => '{{mission}}'));
}

//affichage html de sortie
echo $html;