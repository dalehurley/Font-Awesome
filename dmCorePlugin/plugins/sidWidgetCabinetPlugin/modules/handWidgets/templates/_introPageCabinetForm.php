<?php
echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher l'intro d'une page de cabinet")
	);

echo

$form->renderGlobalErrors(),

_tag('div',
 
    $form['page']->renderRow().
    $form['lenght']->renderRow().
    $form['title_page']->renderRow().
    $form['lien']->renderRow()
          
); 



