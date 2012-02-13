<?php
// vars : $articles, $titreBloc

$html = '';
//titre du contenu
if($titreBloc != null) $html = get_partial('global/titleWidget', array('title' => $titreBloc, 'isContainer' => true));

if(count($articles)){
	//affichage du contenu
	$articleOpts = array(
						'container' => 'article',
						'name' => $articles->getTitle(),
						'description' => $articles->getResume(),
						'image' => $articles->getImage(),
						'dateCreated' => $articles->created_at,
						'dateModified' => $articles->updated_at,
						'articleBody' => $articles->getText(),
						'articleSection' => $titreBloc
					);
	
	//ajout des options de fichiers
	if($articles->getFiles() != null) $articleOpts['uploadFile'] = $articles->getFiles();
	if($articles->getTitleFile() != null) $articleOpts['uploadFileTitle'] = $articles->getTitleFile();
	
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', $articleOpts);
}else{
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', array('container' => 'article', 'articleBody' => '{{actualites_du_cabinet}}'));
}

//affichage html en sortie
echo $html;