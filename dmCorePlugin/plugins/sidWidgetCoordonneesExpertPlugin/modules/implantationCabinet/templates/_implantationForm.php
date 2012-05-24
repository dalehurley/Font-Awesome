<?php
echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher toutes les adresses du cabinet")
	);

echo

$form->renderGlobalErrors(),

_tag('div',
 
    $form['titreBloc']->renderRow() .
    $form['civ']->renderRow().
    $form['resume_team']->renderRow().
    $form['withImage']->renderRow() .
    $form['widthImage']->renderRow() .
    $form['widthImagePhoto']->renderRow() .
    $form['heightImage']->renderRow() .
    $form['cssClass']->renderRow()
    
        
          
); 




