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
if(isset($sites) && $sites != NULL){
echo _tag('h4.title',__('Useful sites of the firm'));
echo _open('ul.elements');
foreach ($sites as $site)
{
    echo _open('li.element');
    echo _link($site)->text(_tag('span.wrapper',_tag('span.title', $site)))->set('.link_box');
    echo _close('li');

}
echo _close('ul');
}