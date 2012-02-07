<?php
// vars : $missions, $titreBloc

$html = '';
//titre du contenu
if($titreBloc != null) $html = get_partial('global/titleWidget', array('title' => $titreBloc, 'isContainer' => true));

// si nous avons des actu articles
if(count($missions)) {
	//affichage du contenu
	$missionOpts = array(
					'container' => 'article',
					'name' => $missions->getTitle(),
					'dateCreated' => $missions->created_at,
					'isDateMeta' => true,
					'description' => $missions->getResume(),
					'articleBody' => $missions->getText()
					);
	//$missionOpts['node'] = $missions;
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', $missionOpts);
	
}else{
	// sinon on affiche rien
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', array('container' => 'article', 'articleBody' => '{{mission}}'));
}

//affichage html de sortie
echo $html;