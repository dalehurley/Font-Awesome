<?php
$html = '';

if (count($pageCabinet)) { // si nous avons des actu articles
	
	//affichage du contenu
	$articleOpts = array(
					'container' => 'article',
					'name' => $titlePage,
					'description' => $pageCabinet->getResume(),
					'image' => $pageCabinet->getImage(),
					'dateCreated' => false,
					'articleBody' => $pageCabinet->getText()
					);
	
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', $articleOpts);
	
	//création d'un tableau de liens à afficher
	$elements = array();
	$elements[] = array('title' => $lien, 'linkUrl' => 'main/contact');
	
	$html.= get_partial('global/navigationWrapper', array(
													'placement' => 'bottom',
													'elements' => $elements
													));
} else {
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', array('container' => 'article', 'articleBody' => '{{page_cabinet}}'));
}

//affichage html de sortie
echo $html;