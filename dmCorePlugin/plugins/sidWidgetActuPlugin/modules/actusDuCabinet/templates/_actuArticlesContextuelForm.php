<?php

echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher les articles d'actu qui sont liés à la même rubrique de la base éditoriale que celle de la page affichée.").
//	_tag('li',"Sur la page d'accueil, sont affichés les articles d'actu mis en avant en home.").	
	_tag('li',"Hors contexte, on renvoie les derniers articles d'actus mise à jour.").
	_tag('li',"Dans la page d'affichage d'une actu du cabinet donné on n'affiche pas l'article dans la liste pour éviter la redondance. ")	
	);


echo

$form->renderGlobalErrors(),


_tag('div',
    $form['type']->renderRow().
    $form['title_page']->renderRow().
    $form['lien']->renderRow().	
    $form['nbArticles']->renderRow().
    $form['length']->renderRow().
    $form['chapo']->renderRow().
    $form['withImage']->renderRow().
    $form['widthImage']->renderRow().
    $form['heightImage']->renderRow()
         
  
); 

