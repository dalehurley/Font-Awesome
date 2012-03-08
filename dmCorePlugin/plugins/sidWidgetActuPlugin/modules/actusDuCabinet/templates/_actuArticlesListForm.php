<?php

echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher la liste des articles d'actu")
	);


echo

$form->renderGlobalErrors(),


_tag('div',
    $form['type']->renderRow().
//    $form['maxPerPage']->renderRow().
//    $form['navTop']->renderRow().
//    $form['navBottom']->renderRow().
    $form['titreBloc']->renderRow().	
    $form['nbArticles']->renderRow().
    $form['length']->renderRow().
    $form['chapo']->renderRow().
    $form['withImage']->renderRow().
    $form['widthImage']->renderRow().
    $form['heightImage']->renderRow().
    $form['cssClass']->renderRow()
         
  
); 

