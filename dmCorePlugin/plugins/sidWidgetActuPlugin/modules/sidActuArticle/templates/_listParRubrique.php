<?php // Vars: $sidActuArticlePager
//if(count($sidActuArticlePager) != NULL){
//echo _tag('h4.title', 'Notre rubrique '.$pageName);
//echo $sidActuArticlePager->renderNavigationTop();
//echo _open('ul.elements');
//
//foreach ($sidActuArticlePager as $sidActuArticle)
//{
//  echo _open('li.element');
//    echo _open('span.wrapper');
//    echo _link($sidActuArticle)->text(_tag('span.title',$sidActuArticle->getTitle())._tag('span.teaser',$sidActuArticle->getResume()));
//    echo _close('span');
//  echo _close('li');
//}
//
//echo _close('ul');
//
//echo $sidActuArticlePager->renderNavigationBottom();
//}


if(count($sidActuArticlePager) != NULL){
echo _tag('h4.title', 'Notre rubrique '.$pageName);
echo $sidActuArticlePager->renderNavigationTop();
echo _open('ul.elements');
	foreach ($sidActuArticlePager as $sidActuArticle) {
            include_partial("objectPartials/actuArticle", array("article" => $sidActuArticle));
	}
echo _close('ul');
echo $sidActuArticlePager->renderNavigationBottom();
}
