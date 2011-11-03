<?php // Vars: $equipePager

//echo $equipePager->renderNavigationTop();
//
//echo _open('ul.elements');
//
//foreach ($equipePager as $equipe)
//{
//  echo _open('li.element');
//
//    echo _link($equipe);
//
//  echo _close('li');
//}
//
//echo _close('ul');
//
//echo $equipePager->renderNavigationBottom();
// Vars: $equipes
/*
echo _tag('h2.couleur', 'Notre équipe, vos interlocuteurs');
echo _open ('div');
foreach ($equipes as $equipe) {
    echo _open('div.trombi#'.$equipe->id);
        echo _open('div.image');
            echo _media($equipe->getImage())->width(60);
        echo _close('div');
        echo _open('div.detail');
            echo _tag('p.couleur.name', $equipe->getTitle() . '-' . $equipe->getStatut());
            echo _open('p.tel');
                echo $equipe->getTel();
            echo _close('p');
            echo _tag('p.tel', 'Email : '.  _link('mailto:'.$equipe->getEmail())->text($equipe->getEmail()));
            echo _tag('div.texte', $equipe->getText());
        echo _close('div');
    echo _close('div');
}
echo _close('div');
 */

echo _tag('h2.title', __('Our team, your partners'));
echo _open('ul.elements');
	foreach ($equipes as $equipe) {
		include_partial("objectPartials/equipe", array("equipe" => $equipe));
	}
echo _close('ul');