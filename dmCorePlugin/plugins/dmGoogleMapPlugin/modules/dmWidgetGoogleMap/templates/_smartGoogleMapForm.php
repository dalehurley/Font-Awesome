<?php
echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher les GoogleMap des différentes adresses")
	);

echo

$form->renderGlobalErrors(),


_tag('div',
 
    //$form['nbArticles']->renderRow().   
    $form['titreBloc']->renderRow().
    $form['cssClass']->renderRow()
); 