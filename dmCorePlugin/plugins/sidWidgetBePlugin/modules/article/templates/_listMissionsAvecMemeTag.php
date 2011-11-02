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

if(isset($missions) && $missions != NULL){
    //echo _open('div.listArticleByTag');
echo _tag('h4.title','Les missions du cabinet');
echo _open('ul.elements');
foreach ($missions as $missionTag)
{
    echo _open('li.element');
    echo _link($missionTag)->text(_tag('span.wrapper',_tag('span.title',$missionTag)))->set('.link_box');
    echo _close('li');

}
echo _close('ul');
//echo _close('div');
}