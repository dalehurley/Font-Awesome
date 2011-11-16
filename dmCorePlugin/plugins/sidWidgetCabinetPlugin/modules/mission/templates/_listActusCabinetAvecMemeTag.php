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
if(isset($actus) && $actus != NULL){
echo _tag('h4.title',__('The news of the firm'));
echo _open('ul.elements');
foreach ($actus as $actuTag)
{
    echo _open('li.element');
    echo _link($actuTag)->text(_tag('span.wrapper',_tag('span.title', $actuTag)))->set('.link_box');
    echo _close('li');

}
echo _close('ul');
}