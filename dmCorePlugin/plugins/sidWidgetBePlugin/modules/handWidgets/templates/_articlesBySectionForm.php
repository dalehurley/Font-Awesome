<?php
echo

$form->renderGlobalErrors(),


_tag('div',
 
    $form['section']->renderRow().
    $form['nbArticles']->renderRow().     
    $form['largeur']->renderRow().
    $form['hauteur']->renderRow()           
  
); 

