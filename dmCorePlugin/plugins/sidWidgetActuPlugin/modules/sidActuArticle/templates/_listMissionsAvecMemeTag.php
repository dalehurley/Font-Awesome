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
if(isset($missions) && $missions != NULL){
echo _tag('h4.title','Les missions du cabinet');
echo _open('ul.elements');
foreach ($missions as $missionTag)
{
include_partial("objectPartials/actuLinkTag", array("tag" => $missionTag));

}
echo _close('ul');
}