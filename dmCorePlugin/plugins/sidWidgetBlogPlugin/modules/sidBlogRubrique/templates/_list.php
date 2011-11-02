<?php // Vars: $sidBlogRubriquePager

echo $sidBlogRubriquePager->renderNavigationTop();

echo _open('ul.elements');

foreach ($sidBlogRubriquePager as $sidBlogRubrique)
{
  echo _open('li.element');

    echo _link($sidBlogRubrique);

  echo _close('li');
}

echo _close('ul');

echo $sidBlogRubriquePager->renderNavigationBottom();