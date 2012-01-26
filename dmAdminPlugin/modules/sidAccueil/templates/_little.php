<?php
// vars $accueilKey , $link

use_stylesheet('core.browsers');
use_stylesheet('admin.dataTable');
use_stylesheet('admin.accueil');
use_javascript('admin.accueils');

echo _tag('div.dm_box',
  _tag('div.title',
    _tag('h2', $accueilKey)
  ).
  _tag('div.dm_box_inner.dm_data.m5',
          $accueilKey
          .'3'
          ._tag('div.dm_box_inner.dm_data.m5',$link)
          )
);