<?php
echo

$form->renderGlobalErrors(),


_tag('div',
 
    
    //$form['nbArticles']->renderRow().   
    $form['photo']->renderRow().
    $form['titreBloc']->renderRow()
  
); 

