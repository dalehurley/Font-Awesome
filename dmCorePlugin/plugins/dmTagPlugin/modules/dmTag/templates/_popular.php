<?php // Vars: $dmTagPager
/*
echo _tag('p', 'Nos articles les plus populaires par mots-cl&eacute;s');
echo _open('ul.elements');

foreach ($dmTags as $dmTag)
{
  echo _open('li.element');

    $tagText = $dmTag->name.' ('.$dmTag->total_num.')';

    if($dmTag->hasDmPage())
    {
      echo _link($dmTag)->text($tagText);
    }
    else
    {
      echo $tagText;
    }

  echo _close('li');
}

echo _close('ul');
*/
use_stylesheet('/theme/css/_templates/'.dmConfig::get('site_theme').'/Widgets/DmTagPopular/DmTagPopular.css');

$style = 1 ;
//echo _tag('h4.title', __('Our most popular items by keyword'));
echo _open('p.elements');

foreach ($dmTags as $dmTag)
{
	if($dmTag->total_num % sfConfig::get('app_dm-tag-plugin-popular-size') == 0)
	{
		$style = $dmTag->total_num / sfConfig::get('app_dm-tag-plugin-popular-size');
	}
	else $style=$style;

	echo _open('span.element.font'.$style);

    $tagText = $dmTag->name;

    if($dmTag->hasDmPage())
    {
      echo _link($dmTag)->text($tagText);
      //echo ' ';
    }
    else
    {
      echo $tagText;
    }
	
	echo _close('span');
	
	//ajout espace de sÃ©paration
	echo ' ';
}
 
echo _close('p');