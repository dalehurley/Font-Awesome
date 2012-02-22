<?php

echo

$form->renderGlobalErrors(),
 _tag('div', $form['titreBloc']->renderRow() .
        $form['lien']->renderRow() .
        $form['nbArticles']->renderRow() .
        $form['length']->renderRow()
);


