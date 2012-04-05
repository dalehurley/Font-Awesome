<?php
echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher la page du recrutement avec un titre de bloc sur mesure")
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



