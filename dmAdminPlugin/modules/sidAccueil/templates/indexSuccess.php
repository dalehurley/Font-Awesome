<?php

echo _open('div.dm_accueils.mt10', array('json' => array(
  'selected' => $selectedIndex
)));

echo _open('ul');
foreach($accueils as $accueilKey => $accueil)
{
  echo _tag('li',
    _link('@dm_accueil?action=show&name='.$accueilKey)
    ->text(__($accueil->getName()))
    ->set('.dm_accueil_link')
  );
}
echo _close('ul');

echo _close('div');