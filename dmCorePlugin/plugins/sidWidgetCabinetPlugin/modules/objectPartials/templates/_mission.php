<?php
// var partial : $mission, $textLength, $textEnd
if (isset($mission)) {
//    if ($length))
//	$textLength = 0;
//    if (!isset($textEnd))
//	$textEnd = ' ...'; 
    
// on affiche soit le résumé de la page (0), soit le texte de la page (1)
if($chapo == 0) $text = $mission->getResume();
elseif ($chapo == 1) $text = $mission->getText();
    echo _open('span.wrapper');
    echo _link($mission)->text(
		    _tag('span.title itemprop="name"', $mission->getTitle()).
//		    _tag('span.teaser itemprop="description"', stringTools::str_truncate($mission->getText(), $textLength, $textEnd))
            _tag('span.teaser itemprop="description"',stringTools::str_truncate($text, $length, '(...)',true))
	    )
	    ->set('.link_box')
	    ->title($mission->getTitle());
    echo _close('span');
} else {
    echo __('This bias needs to be a news item');
}
?>
