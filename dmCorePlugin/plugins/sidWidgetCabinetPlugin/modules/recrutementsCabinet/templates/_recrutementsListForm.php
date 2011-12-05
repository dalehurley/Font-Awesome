<?php

echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher la liste des recrutements")
	);


echo

$form->renderGlobalErrors(),


_tag('div',
    $form['titreBloc']->renderRow().	
    $form['nbRecrutements']->renderRow().
    $form['longueurTexte']->renderRow()
         
  
); 

