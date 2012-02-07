<?php
// vars : $recrutements, $titreBloc

$html = '';
//titre du contenu
if($titreBloc != null) $html = get_partial('global/titleWidget', array('title' => $titreBloc, 'isContainer' => true));

if(count($recrutements)){
	//affichage du contenu
	$recrutementOpts = array(
						'container' => 'article',
						'name' => $recrutements->getTitle(),
						'dateCreated' => $recrutements->created_at,
						'isDateMeta' => true,
						'articleBody' => $recrutements->getText()
						);
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', $recrutementOpts);
	
}else{
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', array('container' => 'article', 'articleBody' => '{{recrutement}}'));
}

//affichage html en sortie
echo $html;