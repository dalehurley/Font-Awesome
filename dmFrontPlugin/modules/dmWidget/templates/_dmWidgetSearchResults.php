<?php
/*
 * Variables available :
 * - $query (string)        the searched query
 * - $form  (mySearchForm)  the search form
 * - $pager (dmSearchPager) the search pager
 */

if (!$pager)
{
  echo _tag('h1', __('No results for "%1%"', array('%1%' => escape($query))));
  return;
}

echo _tag('h2.title', __('Results %1% to %2% of %3%', array(
  '%1%' => $pager->getFirstIndice(),
  '%2%' => $pager->getLastIndice(),
  '%3%' => $pager->getNbResults()
)));

if ($pager->getOption('navigation_top')){
  echo _tag('div.navigation.navigationTop',
      $pager->renderNavigationTop()
    );
} else {
  echo $pager->renderNavigationTop();
}

echo _open('ol.search_results start='.$pager->getFirstIndice());


  //compteur
  $i = 1;
  $i_max = $pager->getLastIndice() - $pager->getFirstIndice() +1;

foreach($pager as $result)
{

  $position = '';
    switch ($i){
        case '1' : 
          if ($i_max == 1) $position = '.first.last';
          else $position = '.first';
            break;
        default : 
          if ($i == $i_max) $position = '.last';
          else $position = '';
          break;
    }
    $i++;

  $page = $result->getPage();
  
  echo _tag('li.search_result'.$position,
  
    _tag('span.score', ceil(100*$result->getScore()).'%').
    
    _link($page)->text(
      _tag('span.page_name', escape($page->name)).
      // ajout lionel
      //dmString::truncate('['.$page->record_id.']', 200)  
      // INFOS : il faut regarder ce que renvoi la fonction getPageContent, il semble qu'elle renvoie filename, et divers id      
      // fin ajout lionel      
      //dmString::truncate($result->getPageContent(), 200)
      _tag('span.page_desc', dmString::truncate($result->getPageContent(), 200))
    )
  );
}

echo _close('ol');

if ($pager->getOption('navigation_bottom')){
  echo _tag('div.navigation.navigationBottom',
      $pager->renderNavigationBottom()
    );
} else {
  echo $pager->renderNavigationBottom();
}