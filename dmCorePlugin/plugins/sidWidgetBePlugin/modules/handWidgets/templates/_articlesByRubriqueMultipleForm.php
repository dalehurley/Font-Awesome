<?php
echo

$form->renderGlobalErrors(),


_tag('div',
 
    $form['titreBloc']->renderRow().
    $form['rubrique']->renderRow().
    $form['nbArticles']->renderRow()     
         
  
); 

