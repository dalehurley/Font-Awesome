<?php

echo

$form->renderGlobalErrors(),

_open('div.dm_tabbed_form'),

_tag('ul.tabs',
  _tag('li', _link('#'.$baseTabId.'_content')->text(__('Content'))).
  _tag('li', _link('#'.$baseTabId.'_advanced')->text(__('Advanced'))).
  _tag('li', _link('#'.$baseTabId.'_scrolltext')->text(__('Scrolling text')))
),


_tag('div#'.$baseTabId.'_content',
  _tag('ul',
    _tag('li.dm_form_element.content.clearfix',
      $form['html']->field()->error()
    )
  )
),

_tag('div#'.$baseTabId.'_advanced',
  _tag('ul',
    _tag('li.dm_form_element.advanced.clearfix',
      $form['perso']->label()->field()->help()->error().
      $form['cssClass']->renderRow()
     )
    )
),

_tag('div#'.$baseTabId.'_scrolltext',
  _tag('ul',
    _tag('li.dm_form_element.scrolltext.clearfix',
      $form['bandeau']->label()->field()->help()->error().
      $form['optionbandeauloop']->label()->field()->error()->help().
      $form['optionbandeaubehavior']->label()->field()->error()->help().
      $form['optionbandeaudirection']->label()->field()->error()->help().
      $form['optionbandeauscrollamount']->label()->field()->error()->help().
      $form['optionbandeauheight']->label()->field()->error()->help().
      $form['optionbandeauwidth']->label()->field()->error()->help()
     )
    )
),

_close('div'); //div.dm_tabbed_form


