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
        echo _tag('h4.title', __('Your contact in this area'));
        echo _open('ul.elements');
        foreach ($equipes as $equipe) {
            echo _open('li.element');
            echo _link($equipe)->text(_tag('span.wrapper',
                    _tag('span.title', $equipe.' - '._tag('span',$equipe->getStatut())) .  _tag('span.teaser', __('phone').' : ' . $equipe->getTel())))->set('.link_box');
            echo _close('li');
        }
        echo _close('ul');
}