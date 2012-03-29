<?php
echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher la page de la selection de placement avec un titre de bloc sur mesure")
	);

echo

$form->renderGlobalErrors(),

_tag('div',
    
    $form['titreBloc']->renderRow().
    $form['withImage']->renderRow().
    $form['widthImage']->renderRow().
    $form['heightImage']->renderRow().
    $form['cssClass']->renderRow()
); 



