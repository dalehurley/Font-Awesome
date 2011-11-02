<?php // Vars: $sectionPager

echo $sectionPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($sectionPager as $section)
{
  echo _open('li.element');

    echo _link($section);

  echo _close('li');
}

echo _close('ul');

echo $sectionPager->renderNavigationBottom();