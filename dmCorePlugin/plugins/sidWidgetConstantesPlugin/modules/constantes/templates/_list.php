<?php // Vars: $constantesPager

echo $constantesPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($constantesPager as $constantes)
{
  echo _open('li.element');

    echo _link($constantes);

  echo _close('li');
}

echo _close('ul');

echo $constantesPager->renderNavigationBottom();