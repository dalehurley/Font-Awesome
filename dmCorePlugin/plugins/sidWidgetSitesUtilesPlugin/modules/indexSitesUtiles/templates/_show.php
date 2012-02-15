<?php // Vars: $indexSitesUtiles
//titre du contenu
$html = get_partial('global/titleWidget', array('title' => $indexSitesUtiles, 'isContainer' => true));

//affichage du contenu
$articleOpts = array('articleBody' => $indexSitesUtiles->description);

$html.= get_partial('global/schema/Thing/CreativeWork/Article', $articleOpts);

//affichage html en sortie
echo $html;