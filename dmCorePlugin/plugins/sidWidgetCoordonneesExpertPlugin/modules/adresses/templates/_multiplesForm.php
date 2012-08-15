<?php
echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher toutes les adresses du cabinet")
	);

echo

$form->renderGlobalErrors(),

_tag('div',
 
    $form['titreBloc']->renderRow().
    $form['gridColumns']->renderRow()    
); 




