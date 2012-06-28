<?php // Vars: $sidAddedPagesPager

echo $sidAddedPagesPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($sidAddedPagesPager as $sidAddedPages)
{
  echo _open('li.element');

    echo _link($sidAddedPages);

  echo _close('li');
}

echo _close('ul');

echo $sidAddedPagesPager->renderNavigationBottom();