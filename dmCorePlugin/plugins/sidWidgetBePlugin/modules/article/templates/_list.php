<?php

// Vars: $articlePager
echo _tag('h4.title', $this->context->getPage()->getName());
$i = 0;
echo $articlePager->renderNavigationTop();

echo _open('ul.elements');

foreach ($articlePager as $article) {
    if ($i < 3) {
        echo _open('li.element');
        $date = new DateTime($article->created_at);

//    $html = _tag('h5.title',$article);
//    $html .= _tag('span.date','('.$date->format('d/m/Y').')');
//if($i == 0){
       
                //lien vers l'image
                $imgLink = '/_images/lea' . $article->filename . '-p.jpg';
                //on vérifie que l'image existe
                $imgExist = is_file(sfConfig::get('sf_web_dir') . $imgLink);

                //composition du html contenu dans le lien (ne peux contenir que des span)
                $html = '';

                if ($imgExist) {
                    $html.= _open('span.imageWrapper');
                    $html.= _media($imgLink)
                            ->set('.image itemprop="image"')
                            ->alt($article->getTitle())
                            ->width(myUser::gridGetWidth(myUser::getLessParam('thumbL_col')))
                            ->height(myUser::gridGetHeight(myUser::getLessParam('thumbL_bl')));
                    $html.= _close('span.imageWrapper');
                }

                $html.= _open('span.wrapper');
                    $html.= _tag('span.title itemprop="name"', $article->getTitle());
                //on ajoute le chapeau dans tous les cas
                $html.= _tag('span.teaser itemprop="description"', $article->getChapeau());
                $html.= _close('span.wrapper');

                //On englobe l'ensemble du contenu dans un lien que l'on affiche
                echo _link($article)
                        ->set('.link_box')
                        ->title($article->getTitle())
                        ->text($html);
                
                $i++;
//    }
//    else {
//    
//        echo _open('span.wrapper');
//        echo _link($article)->text(
//                        _tag('span.title', $article) .
//                        _tag('span.date', '(' . $date->format('d/m/Y') . ')') .
//                        _tag('span.teaser', $article->getChapeau()))
//                ->set('.link_box');
//        echo _close('span');
//        echo _close('li');
//        $i++;
//    }
    } else {
        echo _open('li.element');
        $date = new DateTime($article->created_at);
        echo _open('span.wrapper');
        echo _link($article)->text(
                        _tag('span.title', $article) .
                        _tag('span.date', '(' . $date->format('d/m/Y') . ')'))
                ->set('.link_box');
        echo _close('span');
        echo _close('li');
    }
}

echo _close('ul');

echo $articlePager->renderNavigationBottom();