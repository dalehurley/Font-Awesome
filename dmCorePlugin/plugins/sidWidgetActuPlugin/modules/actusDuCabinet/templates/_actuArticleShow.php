<?php
// vars : $articles, $titreBloc
$html = '';

if(count($articles)){
	
	$pubOpts = array();
							$pubOpts['node']		= $articles;
	if($titreBloc != null)	$pubOpts['category']	= $titreBloc;
	
	$html.= get_partial('global/publicationShow', $pubOpts);
}else{
	$html.= get_partial('global/publicationShow', array('content' => '{{actualites_du_cabinet}}'));
}

//affichage html en sortie
echo $html;