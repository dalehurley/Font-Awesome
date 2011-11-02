<?php // Vars: $sidActuArticlePager
if(count($sidActuArticlePager) != 0){
echo $sidActuArticlePager->renderNavigationTop();
echo _tag('h3', 'Les articles du cabinet');
echo _open('ul.elements');
foreach ($sidActuArticlePager as $sidActuArticle)
{
  echo _open('li.element');

        echo _link($sidActuArticle)->text(_media($sidActuArticle->getImage())->height(100)->method('scale'))->set('.actu_image');
        echo _open('span.actu_bloc');
        echo _link($sidActuArticle)->text(_tag('span.actu_title',$sidActuArticle));
        echo _link($sidActuArticle)->text(_tag('span.actu_rubrique','Sujet : '.$sidActuArticle->getRubrique()));
        echo _link($sidActuArticle)->text(_tag('span.actu_resume',$sidActuArticle->getResume()));
        echo _close('span');

  echo _close('li');
}

echo _close('ul');

echo $sidActuArticlePager->renderNavigationBottom();
}