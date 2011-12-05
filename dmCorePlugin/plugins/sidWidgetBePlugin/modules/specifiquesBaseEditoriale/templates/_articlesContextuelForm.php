<?php

echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher les articles d'actu qui sont liés à une rubrique/section de la base éditoriale.").
	_tag('li',"Sur la page d'accueil, sont affichés les articles d'actu mis en avant en home.").	
	_tag('li',"Hors contexte, on renvoie les derniers articles d'actus mise à jour.").
	_tag('li',"Dans la page d'affichage d'un actu article donné on n'affiche pas l'article dans la liste pour éviter la redondance. ")	
	);


echo

$form->renderGlobalErrors(),


_tag('div',
    $form['titreBloc']->renderRow().
    $form['m_rubriques_list_1']->renderRow().
    $form['titreLien_1']->renderRow().
        $form['m_rubriques_list_2']->renderRow().
    $form['titreLien_2']->renderRow().
        $form['m_rubriques_list_3']->renderRow().
    $form['titreLien_3']->renderRow().
    $form['longueurTexte']->renderRow()
         
  
); 

