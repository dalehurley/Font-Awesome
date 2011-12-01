<?php
echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher la page de cabinet avec un titre et un lien vers page contact sur mesure")
	);

echo

$form->renderGlobalErrors(),

_tag('div',
    
    $form['title_page']->renderRow().
    $form['lien']->renderRow()
); 



