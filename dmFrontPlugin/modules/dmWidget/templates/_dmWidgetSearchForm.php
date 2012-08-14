<?php

if (dmConfig::get('site_theme_version') == 'v1'){
	echo
	$form->open('.clearfix action=main/search method=get'),

	//$form['query']->label()->field('.query'),
	$form['query']->render(array('placeholder' => __('Search').'...', 'class' => 'query')),	

	$form->submit(__('Search')),

	$form->close();
} else {

	// add " \n " to fixed margin's bug in navigator
	echo 
	$form->open('.form-search action=main/search method=get'),

	"
	",

	'<div class="input-append">',

	$form['query']->render(array('placeholder' => __('Search').'...', 'class' => 'input-medium search-query')),	

	"
	",

	$form->renderSubmitTag(__('Search'), array('class' => array('btn'))),

	'</div>',

	$form->close();
}




