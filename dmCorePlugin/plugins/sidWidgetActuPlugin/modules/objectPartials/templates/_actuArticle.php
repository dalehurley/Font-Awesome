<?php
// var partial : $article, $textLength, $textEnd
if (isset($article)) {
    if (!isset($textLength))
	$textLength = 0;
    if (!isset($textEnd))
	$textEnd = ' ...';

    echo _open('li.element itemscope itemtype="http://schema.org/Article"');
//lien vers l'image
    $imgLink = '/uploads/' . $article->getImage();
//on vérifie que l'image existe
    $imgExist = is_file(sfConfig::get('sf_web_dir') . $imgLink);

    if ($imgExist) {
	echo _open('span.imageWrapper');
	echo _media($article->getImage())
		->set('.image itemprop="image"')
		->alt($article->getTitle())
		->width(myUser::gridGetWidth(myUser::getLessParam('thumbX_col'))) 
		->height(myUser::gridGetHeight(myUser::getLessParam('thumbX_bl')));
	echo _close('span.imageWrapper');
    }
    echo _open('span.wrapper');
    echo _link($article)->text(
		    _tag('span.title itemprop="name"', $article->getTitle()) .
		    _tag('span.teaser itemprop="description"', 
			    stringTools::str_truncate(
						myUser::textEditorStripParagraph($article->getResume()), 
						$textLength, 
						$textEnd)
			    ))
	    ->set('.link_box')
	    ->title($article->getTitle());
    echo _close('span');
    echo _close('li');
} else {
    echo "Ce partial a besoin d'un objet actu article";
}
?>
