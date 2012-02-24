<?php
echo

$form->renderGlobalErrors(),


_tag('div',
 
    //$form['nbArticles']->renderRow().   
    $form['recordId']->renderRow().
    $form['cssClass']->renderRow().
    $form['withImage']->renderRow().
    $form['widthImage']->renderRow()
); 

