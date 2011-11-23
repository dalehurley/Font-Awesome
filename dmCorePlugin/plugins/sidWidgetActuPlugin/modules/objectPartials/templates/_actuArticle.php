<?php
// var partial : $article, $textLength, $textEnd
//if (isset($article)) {
//    if ($textLength == false)
//	$textLength = 0;
//    if (!isset($textEnd))
//	$textEnd = ' ...';
//	 
// Si il n'y a que 1 article et que le titreBloc est vide, on enlève le titre de l'article pour le mettre dans la partie supérieure du Bloc
 if($nbArticle == 1){
      if ($titreBloc == ''){ 
           $titre = '';}
// si le titreBloc est rempli, on laisse le titre de l'article dans la partie inférieure du bloc
      else if ($titreBloc != '') $titre = _tag('span.title itemprop="name"', $article->getTitle());
 }
 // On choisit le chapo ou le texte
 if($chapo == 0) $text = $article->getResume();
elseif ($chapo == 1) $text = $article->getText();
// si il y a plusieurs articles, on laisse le titre de chaque articles dans la partie inférieure du bloc
 if($nbArticle > 1 ){ $titre = _tag('span.title itemprop="name"', $article->getTitle());} 
    echo _open('li.element itemscope itemtype="http://schema.org/Article"');
//lien vers l'image
    if($photo == true){
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
    }
    echo _open('span.wrapper');
    echo _link($article)->text(
                                        $titre .
		    _tag('span.teaser itemprop="description"', 
			    stringTools::str_truncate(
						myUser::textEditorStripParagraph($text), 
						$textLength, 
						$textEnd,
                                                                                                            true)
			    ))
	    ->set('.link_box')
	    ->title($article->getTitle());
    echo _close('span');
    echo _close('li');
//} else {
//    echo __('This bias needs to be a news item');
//}
?>
