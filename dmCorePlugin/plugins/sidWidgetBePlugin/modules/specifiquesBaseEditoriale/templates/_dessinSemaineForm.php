<?php

echo

$form->renderGlobalErrors(),
 _tag('div', $form['titreBloc']->renderRow() .
        $form['effect']->renderRow().
        $form['lien']->renderRow().
        $form['filDActu']->renderRow()
);


