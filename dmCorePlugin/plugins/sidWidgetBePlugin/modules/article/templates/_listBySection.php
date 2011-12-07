<?php // Vars: $articlePager, $parent, $route
use_helper('Date');
echo _tag('h2.title', $parent.' - '.$route);

echo _tag('div.navigation.navigationTop', $articlePager->renderNavigationTop());

$i = 0;
// Cas particulier pour les dossiers
//if($this->context->getPage()->getName() == 'Dossiers'){
//    $i=2;
//}

echo _open('ul.elements');
	foreach ($articlePager as $article)
	{
            include_partial("objectPartials/listBySection", array("article" => $article, "i" => $i));
            
	}
echo _close('ul');

echo _tag('div.navigation.navigationBottom', $articlePager->renderNavigationBottom());