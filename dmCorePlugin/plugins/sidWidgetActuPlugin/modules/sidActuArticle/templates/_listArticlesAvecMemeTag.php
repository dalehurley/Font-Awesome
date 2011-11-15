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
if(isset($articles) && $articles != NULL){
echo _tag('h4.title',__('Related articles'));
echo _open('ul.elements');
foreach ($articles as $articleTag)
{
   include_partial("objectPartials/actuLinkTag", array("tag" => $articleTag));

}
echo _close('ul');
}