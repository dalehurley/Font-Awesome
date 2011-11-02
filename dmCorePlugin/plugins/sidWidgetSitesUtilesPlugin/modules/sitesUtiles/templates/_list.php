<?php // Vars: $sitesUtilesPager

echo $sitesUtilesPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($sitesUtilesPager as $sitesUtiles)
{
  echo _open('li.element');

    echo _link($sitesUtiles);

  echo _close('li');
}

echo _close('ul');

echo $sitesUtilesPager->renderNavigationBottom();