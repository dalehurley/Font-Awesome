<?php

echo
$form->open('.clearfix action=main/search method=get'),

//$form['query']->label()->field('.query'),
$form['query']->render(array('placeholder' => __('Search').'...', 'class' => 'query')),	

$form->submit(__('Search')),

$form->close();

echo '
<form class="form-search">
    <input type="text" class="input-medium search-query">
    <button type="submit" class="btn">Search</button>
    </form>';