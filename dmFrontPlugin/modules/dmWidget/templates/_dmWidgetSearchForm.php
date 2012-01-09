<?php

echo
$form->open('action=main/search method=get'),

//$form['query']->label()->field('.query'),
$form['query']->render(array('value' => __('Search').'...', 'class' => 'query')),	

$form->submit(__('Search')),

$form->close();