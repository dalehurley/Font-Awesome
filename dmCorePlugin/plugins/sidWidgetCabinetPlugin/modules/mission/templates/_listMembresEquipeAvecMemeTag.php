<?php // Vars: $missionPager

//echo $missionPager->renderNavigationTop();
//
//echo _open('ul.elements');
//
//foreach ($missionPager as $mission)
//{
//  echo _open('li.element');
//
//    echo _link($mission);
//
//  echo _close('li');
//}
//
//echo _close('ul');
//
//echo $missionPager->renderNavigationBottom();
if (isset($equipes)) {
        echo _tag('h4.title', 'Votre interlocuteur dans ce domaine');
        echo _open('ul.elements');
        foreach ($equipes as $equipe) {
            echo _open('li.element');
            echo _link($equipe)->text(_tag('span.wrapper',
                    _tag('span.title', $equipe.' - '._tag('span',$equipe->getStatut())) .  _tag('span.teaser', 'tél : ' . $equipe->getTel())))->set('.link_box');
            echo _close('li');
        }
        echo _close('ul');
}