<?php // Vars: $groupeSitesUtiles

echo _tag('h2.title',$groupeSitesUtiles.'hhhhh');
echo _open('span.wrapper');
echo _open('span.teaser');
echo $groupeSitesUtiles->getDescription();
echo _close('span.teaser');
echo _close('span.wrapper');
