<?php
echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher les implantations du cabinet")
	);

echo

$form->renderGlobalErrors(),

_tag('div',
 
    $form['titreBloc']->renderRow().
    $form['resume_town']->renderRow().
    $form['length']->renderRow().
    $form['withImage']->renderRow().
    $form['widthImage']->renderRow().
    $form['heightImage']->renderRow()
          
); 




