<?php // Vars: $sitesUtilesPager
if(count($sitesUtilesPager) != 0){
echo $sitesUtilesPager->renderNavigationTop();
echo _tag('h3', 'Les sites utiles');
echo _open('ul.elements');

foreach ($sitesUtilesPager as $sitesUtiles)
{
  echo _open('li.element');

    echo _link($sitesUtiles);

  echo _close('li');
}

echo _close('ul');

echo $sitesUtilesPager->renderNavigationBottom();
}