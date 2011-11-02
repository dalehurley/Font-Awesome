<?php // Vars: $accueil
// j'affiche la présentation de l'équipe si elle existe
if($equipe != null)
{
    echo _open('div.presentation');
    echo _tag('h4.title', 'Notre équipe, vos interlocuteurs');
    echo _tag('div', $equipe);
    echo _link('main/lEquipe')->text('en savoir plus sur notre équipe');
echo _close('div'); // fin div.presentation
}