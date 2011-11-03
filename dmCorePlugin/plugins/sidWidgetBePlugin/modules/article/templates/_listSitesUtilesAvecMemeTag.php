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
if(isset($sites) && $sites != NULL){
   // echo _open('div.listArticleByTag');
echo _tag('h4.title',__('Useful sites of the firm'));
echo _open('ul.elements');
foreach ($sites as $site)
{
    echo _open('li.element');
    echo _link($site)->text(_tag('span.wrapper',_tag('span.title',$site)))->set('.link_box');
    echo _close('li');

}
echo _close('ul');
//echo _close('div');
}