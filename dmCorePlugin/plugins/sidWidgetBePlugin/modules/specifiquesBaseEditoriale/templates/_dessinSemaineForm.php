<?php

echo

$form->renderGlobalErrors(),
 _tag('div', $form['title']->renderRow() .
        $form['effect']->renderRow().
        $form['titreLien']->renderRow().
        $form['filDActu']->renderRow()
);


