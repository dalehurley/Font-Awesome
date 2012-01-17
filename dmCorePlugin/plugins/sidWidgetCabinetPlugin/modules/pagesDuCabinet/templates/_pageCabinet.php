<?php
$html = '';

if (count($pageCabinet)) { // si nous avons des actu articles
	
	//affichage du contenu
	$pubOpts = array();
	$pubOpts['node'] = $pageCabinet;
	$pubOpts['title'] = $titlePage;
	$pubOpts['teaser'] = $pageCabinet->getTitleEntetePage();

	$html.= get_partial('global/publicationShow', $pubOpts);
	
	//création d'un tableau de liens à afficher
	$elements = array();
	$elements[] = array('title' => $lien, 'linkUrl' => 'main/contact');
	
	$html.= get_partial('global/navigationWrapper', array(
													'placement' => 'bottom',
													'elements' => $elements
													));
} else {
	$html.= get_partial('global/publicationShow', array('content' => '{{page_cabinet}}'));
}

//affichage html de sortie
echo $html;