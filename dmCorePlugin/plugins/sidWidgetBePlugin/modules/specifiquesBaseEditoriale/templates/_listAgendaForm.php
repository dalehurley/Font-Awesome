<?php
echo

$form->renderGlobalErrors(),


_tag('div',
 
    
    $form['nbArticles']->renderRow().   
    $form['title']->renderRow().
    $form['lien']->renderRow().
    $form['length']->renderRow()
  
); 

