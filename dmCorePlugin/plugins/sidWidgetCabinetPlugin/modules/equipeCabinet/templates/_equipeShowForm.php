<?php

echo _tag('ul.help', _tag('li', "Ce widget permet d'afficher la liste des membres du cabinet classé par ville d'implantation")
);


echo

$form->renderGlobalErrors(),
 _tag('div', 
     $form['titreBloc']->renderRow().
     $form['withImage']->renderRow().
     $form['widthImage']->renderRow().
     $form['heightImage']->renderRow().
     $form['cssClass']->renderRow()
);


