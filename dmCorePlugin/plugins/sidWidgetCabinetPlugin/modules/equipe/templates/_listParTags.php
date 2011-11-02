<?php // Vars: $equipePager
if(count($equipePager) != 0){
echo _tag('h3', 'Votre interlocuteur dans ce domaine');

echo $equipePager->renderNavigationTop();

echo _open('ul.elements');

foreach ($equipePager as $equipe)
{
  echo _open('li.element');

    echo _link('pageCabinet/equipe')->anchor($equipe->id)->text($equipe);

  echo _close('li');
}

echo _close('ul');

echo $equipePager->renderNavigationBottom();
}