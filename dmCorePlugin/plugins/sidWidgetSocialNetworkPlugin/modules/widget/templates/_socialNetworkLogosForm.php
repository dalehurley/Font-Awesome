<?php

echo

$form->renderGlobalErrors(),
 _tag('div', $form['twitter']->renderRow() .
        $form['facebook']->renderRow().
        $form['googleplus']->renderRow()
);


