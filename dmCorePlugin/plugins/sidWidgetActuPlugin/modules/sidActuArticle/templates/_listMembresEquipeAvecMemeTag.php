<?php // Vars: $sidActuArticlePager

//echo $sidActuArticlePager->renderNavigationTop();
//
//echo _open('ul.elements');
//
//foreach ($sidActuArticlePager as $sidActuArticle)
//{
//  echo _open('li.element');
//
//    echo _link($sidActuArticle);
//
//  echo _close('li');
//}
//
//echo _close('ul');
//
//echo $sidActuArticlePager->renderNavigationBottom();
if (isset($equipes) && $equipes != NULL) {
        echo _tag('h4.title', 'Votre interlocuteur dans ce domaine');
        echo _open('ul.elements');
        foreach ($equipes as $equipe) {
            $member = '';
            $member .= $equipe . ' - ' .$equipe->getStatut() . '<br />tél : ' . $equipe->getTel();
            echo $member;
            include_partial("objectPartials/actuLinkTag", array("tag" => $equipe, "tag->getTitle()" => $member));
        }
        echo _close('ul');
}