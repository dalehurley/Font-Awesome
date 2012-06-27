<?php // Vars: $sidAddedPagesGroupsPager

echo $sidAddedPagesGroupsPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($sidAddedPagesGroupsPager as $sidAddedPagesGroups)
{
  echo _open('li.element');

    echo _link($sidAddedPagesGroups);

  echo _close('li');
}

echo _close('ul');

echo $sidAddedPagesGroupsPager->renderNavigationBottom();