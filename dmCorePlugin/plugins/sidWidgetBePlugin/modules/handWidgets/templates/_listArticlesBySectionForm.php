<?php
echo

$form->renderGlobalErrors(),


_tag('div',
 
    $form['section']->renderRow().
    $form['nbArticles']->renderRow().   
    $form['title']->renderRow().
    $form['photo']->renderRow().
    $form['titreBloc']->renderRow()        
  
); 

