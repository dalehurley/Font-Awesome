<?php // Vars: $articlePager
//
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
if(isset($actus) && $actus != NULL){
    //echo _open('div.listArticleByTag');
echo _tag('h4.title','Les actus du cabinet');
echo _open('ul.elements');
foreach ($actus as $actuTag)
{
    echo _open('li.element');
    echo _link($actuTag)->text(_tag('span.wrapper',_tag('span.title',$actuTag)))->set('.link_box');
    echo _close('li');

}
echo _close('ul');
//echo _close('div');
}