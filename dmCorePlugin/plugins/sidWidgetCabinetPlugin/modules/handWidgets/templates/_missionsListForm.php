<?php

echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher la liste des missions")
	);


echo

$form->renderGlobalErrors(),


_tag('div',
    $form['titreBloc']->renderRow().	
    $form['nbMissions']->renderRow().
    $form['longueurTexte']->renderRow()//.
//    $form['chapo']->renderRow()
         
  
); 

