<?php

echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher les articles d'actu qui sont liés à une rubrique/section de la base éditoriale.").
	_tag('li',"Dans la page d'affichage d'une actu article donné on affiche le bloc des dossiers. ").
        _tag('li',"Dans la page d'affichage d'une actu dossier donné on n'affiche pas le bloc des dossiers pour éviter la redondance, ").
        _tag('li',"mais les actu article de la section. ")
	);


echo

$form->renderGlobalErrors(),


_tag('div',
    $form['titreBloc']->renderRow().
    $form['section']->renderRow().
    $form['lien']->renderRow().
    $form['nbArticles']->renderRow().
    $form['length']->renderRow().
    $form['isDossier']->renderRow().
    $form['visibleInDossier']->renderRow()
         
  
); 

