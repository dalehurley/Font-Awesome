<?php

echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher la page de l'actu du cabinet")
	);


echo

$form->renderGlobalErrors(),


_tag('div',
    $form['type']->renderRow().
    $form['titreBloc']->renderRow().
    $form['cssClass']->renderRow().
    $form['widthImage']->renderRow().
    $form['heightImage']->renderRow()
  
); 

