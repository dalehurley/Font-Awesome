<?php

echo

$form->renderGlobalErrors(),

_open('div.dm_tabbed_form'),

_tag('ul.tabs',
  _tag('li', _link('#'.$baseTabId.'_items')->text(__('Items'))).
  _tag('li', _link('#'.$baseTabId.'_advanced')->text(__('Advanced')))
);

if (dmConfig::get('site_theme_version') == 'v2'){    
  echo _tag('div#'.$baseTabId.'_items.drop_zone',
    _tag('ol.items_list', array('json' => array(
      'items' => $items,
      'extended_show_message' => __('Show extended options'),
      'extended_hide_message' => __('Hide extended options'),
      'delete_message' => __('Remove'),
      'text_message' => __('Text'),
      'text_help' => _tag('div.dm_help',__('Laisser vide pour faire apparaitre un séparateur')),      
      'link_message' => __('Link'),
      'groupdisplayed_message' => __('Groups displayed'),
      'groupdisplayed_help' => _tag('div.dm_help',__('WARNING: Non utilisé dans les thème en V2')),       
      'secure_message' => __('Requires authentication'),
      'nofollow_message' => __('No follow'),
      'depth_message' => __('Depth'),
      'depth_help' => _tag('div.dm_help',__('WARNING: O ou 1 pour les thème V2 (pas de sous-niveau pour les thèmes V2)')),   
      'target_message' => __('Target'),
      'click_message' => __('Click to edit, drag to sort')
    )), '').
    _tag('div.dm_help.no_margin',
      __('Drag & drop links here from the left PAGE panel').
      '<br />'.
      _tag('a.external_link', __('or create an external link').'  '.__('or create a separator'))
    )
  );
} else {
  echo _tag('div#'.$baseTabId.'_items.drop_zone',
    _tag('ol.items_list', array('json' => array(
      'items' => $items,
      'extended_show_message' => __('Show extended options'),
      'extended_hide_message' => __('Hide extended options'),
      'delete_message' => __('Remove'),
      'text_message' => __('Text'),
      'link_message' => __('Link'),
      'groupdisplayed_message' => __('Groups displayed'),
      'groupdisplayed_help' => _tag('div.dm_help',__('Choisir le groupe pour ce menu : <br/>
  - un libellé de groupe = on affiche les pages ayant ce groupe<br/>
  - * = pour afficher toutes les pages<br/>
  - laisser vide = pour afficher les pages SANS groupe')),  
      'secure_message' => __('Requires authentication'),
      'nofollow_message' => __('No follow'),
      'depth_message' => __('Depth'),
      'target_message' => __('Target'),
      'click_message' => __('Click to edit, drag to sort')
    )), '').
    _tag('div.dm_help.no_margin',
      __('Drag & drop links here from the left PAGE panel').
      '<br />'.
      _tag('a.external_link', __('or create an external link'))
    )
  );
}

echo _tag('div#'.$baseTabId.'_advanced',
  _tag('ul.dm_form_elements',
    $form['menuName']->renderRow().
    $form['menuType']->renderRow().          
    $form['cssClass']->renderRow().
    $form['ulClass']->renderRow().
    $form['liClass']->renderRow().
    (isset($form['menuClass']) ? $form['menuClass']->renderRow() : '')
  )
),

_close('div'); //div.dm_tabbed_form
