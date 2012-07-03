<?php

use_javascript('lib.jstree');
use_javascript('admin.modelTree');

echo _open('div.dm_sort.dm_box.big');

  //echo _tag('h1.title', __('Sort %1%', array('%1%' => $form->getModule()->getPlural())));
  // if(sfContext::getInstance()->getModuleName()== 'sidAddedPagesAdmin'){
  //     echo _open('div', array('class'=>'text_align_right'));
  //     echo _tag('span',array('class'=>'info'),'Faire F5 pour sauvegarder vos modifications');
  //     echo _close('div');
  // }
  echo _open('div.dm_box_inner');

    echo _open('div#dm_full_model_tree.clearfix.dm');
      echo $tree->render();

    echo _close('div');

  echo _close('div');

echo _close('div');