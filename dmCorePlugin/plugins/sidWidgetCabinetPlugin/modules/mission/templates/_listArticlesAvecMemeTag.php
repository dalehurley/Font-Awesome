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
if(isset($articles) && $articles != NULL){
echo _tag('h4.title',__('Related articles'));
echo _open('ul.elements');
foreach ($articles as $articleTag)
{
    echo _open('li.element');
    echo _link($articleTag)->text(_tag('span.wrapper',_tag('span.title', $articleTag)))->set('.link_box');
    echo _close('li');

}
echo _close('ul');
}