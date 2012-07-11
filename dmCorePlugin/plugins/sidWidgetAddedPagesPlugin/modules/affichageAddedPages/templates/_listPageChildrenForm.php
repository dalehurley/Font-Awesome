<?php
echo

$form->renderGlobalErrors(),


_tag('div',
 
    //$form['nbArticles']->renderRow(). 
    $form['titreBloc']->renderRow().
    $form['nbArticles']->renderRow().
    $form['length']->renderRow().
    $form['withImage']->renderRow().
    $form['nbImages']->renderRow().
    $form['widthImage']->renderRow().
    $form['heightImage']->renderRow().
    $form['withDate']->renderRow().
    $form['withResume']->renderRow().
    $form['cssClass']->renderRow()
); 

