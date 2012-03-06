<?php
echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher la liste des actualités par ordre chronologique sous les article de BE")
	);

echo

$form->renderGlobalErrors(),
 _tag('div', 
    $form['titreBloc']->renderRow().	
    $form['nbArticles']->renderRow().
    $form['length']->renderRow().
    $form['chapo']->renderRow().
    $form['withImage']->renderRow().
    $form['widthImage']->renderRow().
    $form['heightImage']->renderRow().
    $form['cssClass']->renderRow()
);


