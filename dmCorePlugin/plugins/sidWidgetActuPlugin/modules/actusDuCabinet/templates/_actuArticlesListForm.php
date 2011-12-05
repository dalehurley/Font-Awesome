<?php

echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher la liste des articles d'actu")
	);


echo

$form->renderGlobalErrors(),


_tag('div',
    $form['type']->renderRow().
    $form['titreBloc']->renderRow().	
    $form['nbArticles']->renderRow().
    $form['longueurTexte']->renderRow().
    $form['photo']->renderRow().
    $form['chapo']->renderRow()
         
  
); 

