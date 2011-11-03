<?php
// Vars: $equipes
echo _tag('h2.couleur', __('Our team, your partners'));
echo _open ('div');
foreach ($equipes as $equipe) {
    echo _open('div.trombi');
        echo _open('div.image');
            echo _media($equipe->getImage())->width(60);
        echo _close('div');
        echo _open('div.detail');
            echo _tag('p.couleur.name', $equipe->getTitle() . '-' . $equipe->getStatut());
            echo _open('p.tel');
                echo $equipe->getTel();
            echo _close('p');
            echo _tag('p.tel', __('Email').' : '.  _link('mailto:'.$equipe->getEmail())->text($equipe->getEmail()));
            echo _tag('div.texte', $equipe->getText());
        echo _close('div');
    echo _close('div');
}
echo _close('div');