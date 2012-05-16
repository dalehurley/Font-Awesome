<?php
echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher les mentions légales du cabinet")
	);

echo

$form->renderGlobalErrors(),

_tag('div',
    $form['titreBloc']->renderRow().
    $form['defaultInfos']->renderRow().
    $form['text']->render(array('class' => 'dm_markdown'))
          
); 




