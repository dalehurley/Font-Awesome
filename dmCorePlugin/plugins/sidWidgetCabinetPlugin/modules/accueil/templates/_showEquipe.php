<?php // Vars: $accueil
// j'affiche la présentation de l'équipe si elle existe
if($equipe != null)
{
    echo _open('div.presentation');
    echo _tag('h4.title', __('Our team, your partners'));
    echo _tag('div', $equipe);
    echo _link('main/lEquipe')->text(__('Learn more about our team'));
echo _close('div'); // fin div.presentation
}