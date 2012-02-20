<?php
echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher l'intro d'une page de cabinet")
	);

echo

$form->renderGlobalErrors(),

_tag('div',
 
    $form['page']->renderRow().
    $form['length']->renderRow().
    $form['titreBloc']->renderRow().
    $form['lien']->renderRow().
    $form['withImage']->renderRow().
    $form['widthImage']->renderRow().
    $form['heightImage']->renderRow()
          
);




