<?php // Vars: $accueil
if($mission != null)
{
echo _open('div.presentation');
    echo _tag('h4.title', __('Our missions'));
    echo _tag('div', $mission);
    echo _link('mission/list')->text(__('Learn more about our missions'));
echo _close('div'); // fin div.presentation
}