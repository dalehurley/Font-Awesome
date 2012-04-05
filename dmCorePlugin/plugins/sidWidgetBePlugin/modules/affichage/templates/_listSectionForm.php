<?php
echo

$form->renderGlobalErrors(),


_tag('div',
 
    $form['recordId']->renderRow().
    $form['nbSections']->renderRow(). 
    $form['nbArticles']->renderRow().
    $form['cssClass']->renderRow().
    $form['withImage']->renderRow().
    $form['widthImage']->renderRow()
); 

