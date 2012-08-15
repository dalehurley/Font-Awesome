<?php // Vars: $rubriquePager

if (dmConfig::get('site_theme_version') == 'v1'){

	echo $rubriquePager->renderNavigationTop();

	echo _open('ul.elements');

	foreach ($rubriquePager as $rubrique)
	{
	  echo _open('li.element');

	    echo _link($rubrique);

	  echo _close('li');
	}

	echo _close('ul');

	echo $rubriquePager->renderNavigationBottom();

} elseif (dmConfig::get('site_theme_version') == 'v2'){
	
	echo $rubriquePager->renderNavigationTop();

	echo _open('ul.thumbnails');

	foreach ($rubriquePager as $rubrique)
	{
	  echo _open('li');

	    echo _link($rubrique)->set('.thumbnail');

	  echo _close('li');
	}

	echo _close('ul');

	echo $rubriquePager->renderNavigationBottom();
}