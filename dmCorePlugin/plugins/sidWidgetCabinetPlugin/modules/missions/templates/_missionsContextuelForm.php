<?php

echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher les missions de l'équipe qui sont liés à une rubrique/section de la base éditoriale.").
	_tag('li',"Hors contexte rien ne s'affiche.")	
	);

echo

$form->renderGlobalErrors(),


_tag('div',
    $form['title_page']->renderRow().
    $form['lien']->renderRow().
    $form['nbArticles']->renderRow().
    $form['length']->renderRow().
    $form['chapo']->renderRow().
    $form['withImage']->renderRow().
    $form['widthImage']->renderRow().
    $form['heightImage']->renderRow()
    
         
  
); 



