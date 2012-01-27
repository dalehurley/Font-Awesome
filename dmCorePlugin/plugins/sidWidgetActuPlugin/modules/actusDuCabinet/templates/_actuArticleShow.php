<?php
// vars : $articles, $titreBloc

$html = '';
//titre du contenu
if($titreBloc != null) $html = get_partial('global/titleWidget', array('title' => $titreBloc, 'isContainer' => true));

if(count($articles)){
	//affichage du contenu
	$articleOpts = array('container' => 'article');
	$articleOpts['node'] = $articles;
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', $articleOpts);
}else{
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', array('container' => 'article', 'articleBody' => '{{actualites_du_cabinet}}'));
}

//affichage html en sortie
echo $html;