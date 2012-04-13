<?php

echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher le bloc du lien vers la page de contact")
	);


echo

$form->renderGlobalErrors(),


_tag('div',
    $form['titreBloc']->renderRow().
    $form['message']->renderRow().
    $form['lien']->renderRow().
    $form['href']->renderRow().
    $form['cssClass']->renderRow()
  
); 

