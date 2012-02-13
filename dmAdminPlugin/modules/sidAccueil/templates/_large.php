<?php
// vars $accueilMessage , $moduleLink

use_stylesheet('core.browsers');
use_stylesheet('admin.dataTable');
use_stylesheet('admin.accueil');


echo _tag('div#accueil.dm_box_perso',
//  _tag('div.title',
//    _tag('h2', $accueilMessage)
//  ).
  _tag('div.dm_box_inner.dm_data.m5', $html
//          _tag('div.dm_box_inner.dm_data.m5',$link)
          )
);