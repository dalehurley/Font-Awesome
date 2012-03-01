<?php
echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher la page de cabinet avec un titre et un lien vers page contact sur mesure")
	);

echo

$form->renderGlobalErrors(),

_tag('div',
    
    $form['titreBloc']->renderRow().
    $form['lien']->renderRow().
    $form['withImage']->renderRow().
    $form['widthImage']->renderRow().
    $form['heightImage']->renderRow()
); 



