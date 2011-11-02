<?php // Vars: $sidActuArticlePager

echo $sidActuArticlePager->renderNavigationTop();

echo _open('ul.elements');

foreach ($sidActuArticlePager as $sidActuArticle)
{
  echo _open('li.element');

    echo _link($sidActuArticle);

  echo _close('li');
}

echo _close('ul');

echo $sidActuArticlePager->renderNavigationBottom();