<?php // Vars: $equipePager

echo _tag('h2.title', __('Our team, your partners'));
echo $equipePager->renderNavigationTop();
echo _open('ul.elements');
	foreach ($equipePager as $equipe) {
                    include_partial("objectPartials/equipe", array("equipe" => $equipe));
	}
echo _close('ul');
echo $equipePager->renderNavigationBottom();