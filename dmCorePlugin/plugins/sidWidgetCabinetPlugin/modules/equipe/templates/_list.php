<?php // Vars: $equipePager

echo $equipePager->renderNavigationTop();

echo _open('ul.elements');

foreach ($equipePager as $equipe)
{
  echo _open('li.element');

    echo _link($equipe);

  echo _close('li');
}

echo _close('ul');

echo $equipePager->renderNavigationBottom();