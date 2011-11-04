<?php
// var partial : $mission, $textLength, $textEnd
if (isset($mission)) {
    if (!isset($textLength))
	$textLength = 0;
    if (!isset($textEnd))
	$textEnd = ' ...'; 

    echo _open('span.wrapper');
    echo _link($mission)->text(
		    _tag('span.title itemprop="name"', $mission->getTitle()) .
		    _tag('span.teaser itemprop="description"', stringTools::str_truncate($mission->getText(), $textLength, $textEnd))
	    )
	    ->set('.link_box')
	    ->title($mission->getTitle());
    echo _close('span');
    echo _close('li');
} else {
    echo __('This bias needs to be a news item');
}
?>
