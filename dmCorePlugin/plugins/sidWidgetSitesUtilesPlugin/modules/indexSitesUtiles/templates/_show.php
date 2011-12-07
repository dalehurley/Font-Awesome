<?php // Vars: $indexSitesUtiles

echo _tag('h2.title',$indexSitesUtiles);
echo _open('span.wrapper');
echo _open('span.teaser');
echo $indexSitesUtiles->description;
echo _close('span.teaser');
echo _close('span.wrapper');