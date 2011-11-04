<?php // Vars: $articlePager

//echo $articlePager->renderNavigationTop();
//
//echo _open('ul.elements');
//
//foreach ($articlePager as $article)
//{
//  echo _open('li.element');
//
//    echo _link($article);
//
//  echo _close('li');
//}
//
//echo _close('ul');
//
//echo $articlePager->renderNavigationBottom();

// Vars: $article

if (isset($equipes) && $equipes != NULL) {
    //echo _open('div.listArticleByTag');
        echo _tag('h4.title', __('Your contact in this area'));
        echo _open('ul.elements');
        foreach ($equipes as $equipe) {
            echo _open('li.element');
            echo _link('pageCabinet/equipe')->anchor($equipe->id)->text(_tag('sapn.wrapper',_tag('span.title', $equipe) . ' - ' . _tag('span.statut', $equipe->getStatut()) . '<br />' . _tag('span.tel', __('phone').'  : ' . $equipe->getTel())))->set('.link_box');
            echo _close('li');
        }
        echo _close('ul');

    //echo _close('div');
}