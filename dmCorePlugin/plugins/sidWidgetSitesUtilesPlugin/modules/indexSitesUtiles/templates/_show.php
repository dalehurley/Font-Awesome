<?php // Vars: $indexSitesUtiles
//titre du contenu
/*
//affichage du contenu
$articleOpts = array('articleBody' => $indexSitesUtiles->description);

$html.= get_partial('global/schema/Thing/CreativeWork/Article', $articleOpts);

//affichage html en sortie
echo $html;
*/

if(count($indexSitesUtiles)){

echo _tag('h2.title',$indexSitesUtiles->getTitle());
echo _tag('section', array('class' => 'contentBody', 'itemprop' => 'articleBody'), $indexSitesUtiles->getDescription());
}