<?php
echo _tag('ul.help',
	_tag('li',"Ce widget permet d'afficher les membres de l'équipe qui sont liés à une rubrique/section de la base éditoriale.").
	_tag('li',"Hors contexte rien ne s'affiche.")
	);

echo

$form->renderGlobalErrors(),


_tag('div',
 
    $form['titreBloc']->renderRow().
    $form['titreLien']->renderRow().	
    $form['nb']->renderRow().
    $form['length']->renderRow()
         
  
); 



