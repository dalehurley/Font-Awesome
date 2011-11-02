<?php // Vars: $groupeSitesUtilesPager

echo $groupeSitesUtilesPager->renderNavigationTop();

//echo _tag('h2', $pageTitle);

echo _open('ul.elements');

foreach ($groupeSitesUtilesPager as $groupeSitesUtiles)
{
  echo _open('li.element');

    echo _link($groupeSitesUtiles);

  echo _close('li');
}

echo _close('ul');

echo $groupeSitesUtilesPager->renderNavigationBottom();