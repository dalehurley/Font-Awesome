<?php
echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher la page de la mission avec un titre de bloc sur mesure")
	);

echo

$form->renderGlobalErrors(),

_tag('div',
    
    $form['titreBloc']->renderRow()
); 



