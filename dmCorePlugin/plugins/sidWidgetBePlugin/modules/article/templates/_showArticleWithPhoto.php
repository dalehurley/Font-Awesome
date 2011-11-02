<?php // Vars: $lastArticle
if(isset($lastArticle)){
echo _tag('h3',_link($lastArticle['titre']));
echo _open('div');
echo _tag('p',_link($lastArticle['titre'])->text($lastArticle['chapeau']));
echo _close('div');
echo _open('div');
echo _link($lastArticle['titre'])->text(_media('/_images/lea' . $lastArticle['filename'] . '-g.jpg')->width(170)->alt($lastArticle['titre']));
echo _close('div');
echo _link('/rubriques/actualites')->text('TOUTES LES ACTUALITES');
}