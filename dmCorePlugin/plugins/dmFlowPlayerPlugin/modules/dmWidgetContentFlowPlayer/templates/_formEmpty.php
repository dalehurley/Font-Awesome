<?php

echo

$form->renderGlobalErrors(),

_open('div.dm_tabbed_form'),

_tag('ul.tabs',
// ** stef
// rajout pour activer le bloc de pub
  _tag('li', _link('#'.$baseTabId.'_blocPub')->text(__('Block adds'))).
// fin rajout
  _tag('li', _link('#'.$baseTabId.'_media')->text(__('Media')))
),

// ** stef
// rajout pour activer le bloc de pub
_tag('div#'.$baseTabId.'_blocPub',
  _tag('ul',
    $form['checkPubs']->renderRow().
    $form['pubsId']->renderRow()
  )
),
// fin rajout        
_tag('div#'.$baseTabId.'_media',
  
  _tag('div.toggle_group',

    $form['mediaId']->render(array('class' => 'dm_media_id')).

    _tag('ul.media_fields',
      $form['mediaName']->renderRow().
      $form['file']->renderRow()
    )
  )
 



);

_close('div'); //div.dm_tabbed_form