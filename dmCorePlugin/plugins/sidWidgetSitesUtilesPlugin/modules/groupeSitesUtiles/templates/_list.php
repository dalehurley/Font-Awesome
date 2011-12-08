<?php // Vars: $groupeSitesUtilesPager
echo _tag('h4.title', __('Different groups of useful sites'));
echo $groupeSitesUtilesPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($groupeSitesUtilesPager as $groupeSitesUtiles)
{
  echo _open('li.element');

    echo _link($groupeSitesUtiles)->set('link_box')->text(_tag('span.wrapper',_tag('span.title', $groupeSitesUtiles)));

  echo _close('li');
}

echo _close('ul');

echo $groupeSitesUtilesPager->renderNavigationBottom();