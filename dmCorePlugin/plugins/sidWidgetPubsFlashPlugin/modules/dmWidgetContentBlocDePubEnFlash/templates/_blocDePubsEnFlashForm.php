<?php

echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher la pub en aléatoire à chaque chargement de page")
	);


echo

$form->renderGlobalErrors(),


_tag('div',
    $form['pubsId']->renderRow().
    $form['width']->renderRow().
    $form['height']->renderRow()
  
); 

