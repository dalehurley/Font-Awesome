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
    $form['withImage']->renderRow().
    //$form['widthImage']->renderRow(). on prend pour l'instant que la hauteur en paramètre
    $form['heightImage']->renderRow()
  
); 

