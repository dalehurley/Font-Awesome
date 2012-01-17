<?php
// vars : $recrutements, $titreBloc
$html = '';

if(count($recrutements)){
	
	$pubOpts = array();
							$pubOpts['node']		= $recrutements;
	if($titreBloc != null)	$pubOpts['category']	= $titreBloc;
	
	$html.= get_partial('global/publicationShow', $pubOpts);
}else{
	$html.= get_partial('global/publicationShow', array('content' => '{{recrutement}}'));
}

//affichage html en sortie
echo $html;