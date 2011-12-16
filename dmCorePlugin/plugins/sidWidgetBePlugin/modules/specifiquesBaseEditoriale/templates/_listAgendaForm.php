<?php

echo

$form->renderGlobalErrors(),
 _tag('div', $form['title']->renderRow() .
        $form['lien']->renderRow() .
        $form['nbArticles']->renderRow() .
        $form['length']->renderRow().
        $form['pageCentrale']->renderRow()
);


