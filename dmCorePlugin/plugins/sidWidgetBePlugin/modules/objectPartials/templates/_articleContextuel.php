<?php

foreach ($articles as $article) {
echo _open('li.element itemscope itemtype="http://schema.org/Article"');
    //lien vers l'image
    $imgLink = '/_images/lea' . $article->filename . '-p.jpg';
    //on vérifie que l'image existe
    $imgExist = is_file(sfConfig::get('sf_web_dir') . $imgLink);

    //composition du html contenu dans le lien (ne peux contenir que des span)
    $html = '';

    if ($photoArticle == true && $imgExist) {
            $html.= _open('span.imageWrapper');
                    $html.= _media($imgLink)
                                    ->set('.image itemprop="image"')
                                    ->alt($article->getTitle())
                                    ->width(spLessCss::gridGetWidth(spLessCss::getLessParam('thumbL_col')))
        ->height(spLessCss::gridGetHeight(spLessCss::getLessParam('thumbL_bl')));
            $html.= _close('span.imageWrapper');
    }

    $html.= _open('span.wrapper');
            if ($titreBloc == false || (count($articles) > 1)) {
                    $html.= _tag('span.title itemprop="name"', $article->getTitle());
            }
            //on ajoute le chapeau dans tous les cas

            $html.= _tag('span.teaser itemprop="description"', stringTools::str_truncate(
					    myUser::textEditorStripParagraph($article->getChapeau()), $textLength, $textEnd,true));
    $html.= _close('span.wrapper');
    
    
    
    //On englobe l'ensemble du contenu dans un lien que l'on affiche
    echo _link($article)
            ->set('.link_box')
            ->title($article->getTitle())
            ->text($html);
    echo _link($article->Section)->set('.link_box')->title($article->Section)->text($lien[$article->id]);
echo _close('li');
}
?>
