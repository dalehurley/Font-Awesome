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
if(isset($sites) && $sites != NULL){
echo _tag('h4.title','Les sites utiles du cabinet');
echo _open('ul.elements');
foreach ($sites as $site)
{
    echo _open('li.element');
    echo _link($site)->text(_tag('span.wrapper',_tag('span.title',$site)))->set('.link_box');
    echo _close('li');

}
echo _close('ul');
}