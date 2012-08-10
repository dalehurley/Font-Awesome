<?php // Vars: $groupeSitesUtiles
//titre du contenu
echo '******************************************************************************************toto');
$html = get_partial('global/titleWidget', array('title' => $groupeSitesUtiles, 'isContainer' => true));

//affichage du contenu
$articleOpts = array('articleBody' => $groupeSitesUtiles->getDescription());

$html.= get_partial('global/schema/Thing/CreativeWork/Article', $articleOpts);

//affichage html en sortie
echo $html;