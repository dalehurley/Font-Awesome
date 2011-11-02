<?php // Vars: $missionPager

echo $missionPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($missionPager as $mission)
{
  echo _open('li.element');

    echo _link($mission);

  echo _close('li');
}

echo _close('ul');

echo $missionPager->renderNavigationBottom();