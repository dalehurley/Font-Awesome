<?php

echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher les flux rss")
	);


echo

$form->renderGlobalErrors(),


_tag('div',
    $form['url']->renderRow().
    $form['title']->renderRow().	
    $form['nbArticles']->renderRow().
    $form['length']->renderRow().
    $form['life_time']->renderRow().
    $form['logo_les_echos']->renderRow().
    $form['cssClass']->renderRow()
         
  
); 
