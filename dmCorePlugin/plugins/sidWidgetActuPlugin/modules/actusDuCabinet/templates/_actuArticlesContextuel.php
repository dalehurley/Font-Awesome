<?php
// vars : $articles, $nbArticles, $titreBloc, $lien, $length, $chapo, $width, $height
if(dmConfig::get('site_theme_version') == 'v1'){
    //Récupération des variables
    $ellipsis = _tag('span.ellipsis', sfConfig::get('app_vars-partial_ellipsis'));
    $i = 1;
    $i_max = count($articles);

    if (count($articles)) { // si nous avons des actu articles
        //gestion affichage du titre
        echo _tag('h4.title',$titreBloc);

        echo _open('ul', array('class' => 'elements'));
        foreach ($articles as $article) {
            $link = '';

            //définition des options du li
            $ctnOpts = array('class' => array('element', 'itemscope', 'Article'), 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope');
            if($i == 1)         $ctnOpts['class'][] = 'first';
            if($i >= $i_max)    $ctnOpts['class'][] = 'last';

            echo _open('li', $ctnOpts);
            
            if ($withImage == true) {
                if (($article->getImage()->checkFileExists() == true) and ($i <= sfConfig::get('app_nb-image'))) {
                    $link .= _open('span', array('class' => 'imageWrapper'));
                    $link .= _media($article->getImage())->width($width)->set('.image itemprop="image"')->alt($article->getTitle());
                    $link .= _close('span');
                }
            };
            $link .= _open('span' , array('class' => 'wrapper'));
                $link .= _open('span' , array('class' => 'subWrapper'));
                    if ($titreBloc != $article->getTitle()) {
                        $link .= _tag('span', array('class' => array('title', 'itemprop', 'name'), 'itemprop' => 'name'), $article->getTitle());
                    };
                    $link .= _tag('meta' , array('content' => $article->createdAt, 'itemprop' => 'datePublished'));
                $link .= _close('span');
                $link .= _open('span', array('class' => array('teaser', 'itemprop', 'description') , 'itemprop' => 'description'));
                   if ($chapo == 0) {
                       $link .= stringTools::str_truncate($article->getResume(), $length, $ellipsis, true);
                   }
                   else if ($chapo == 1) {
                       $link .= $article->getText();
                   }
                $link .= _close('span');
            $link .= _close('span');

            echo _link($article)->set('.link_box')->text($link);

            echo _close('li');
            $i++;
        } 
    echo _close('ul');
    if ((isset($lien)) AND ($lien != '')) { 
            echo _open('div', array('class' => 'navigationWrapper navigationBottom'));
                echo _open('ul', array('class' => 'elements'));
                    echo _tag('li', array('class' => 'element first last'), 
                            _link('sidActuArticle/list')->text($lien)
                            );
                echo _close('ul');
            echo _close('div');
        
        }
    } // sinon on affiche rien
}
elseif(dmConfig::get('site_theme_version') == 'v2'){
    $ellipsis = _tag('span.ellipsis', sfConfig::get('app_vars-partial_ellipsis'));
    $i = 1;
    $i_max = count($articles);

    if (count($articles)) { // si nous avons des actu articles
        //gestion affichage du titre
        echo _tag('h3',$titreBloc);

        echo _open('ul', array('class' => 'thumbnails'));
        foreach ($articles as $article) {
            $link = '';

            //définition des options du li
            $ctnOpts = array('itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope');
            if($i == 1)         $ctnOpts['class'][] = 'first';
            if($i >= $i_max)    $ctnOpts['class'][] = 'last';

            echo _open('li', $ctnOpts);
            
            if ($withImage == true) {
                if (($article->getImage()->checkFileExists() == true) and ($i <= sfConfig::get('app_nb-image'))) {
                    $link .= _media($article->getImage())->width($width)->set('itemprop="image"')->alt($article->getTitle());
                }
            };
            $link .= _open('div' , array('class' => 'caption'));
                    if ($titreBloc != $article->getTitle()) {
                        $link .= _tag('h5', array('class' => array('itemprop', 'name'), 'itemprop' => 'name'), $article->getTitle());
                    };
                    $link .= _tag('meta' , array('content' => $article->createdAt, 'itemprop' => 'datePublished'));
                $link .= _open('p', array('class' => array('itemprop', 'description') , 'itemprop' => 'description'));
                   if ($chapo == 0) {
                       $link .= stringTools::str_truncate($article->getResume(), $length, $ellipsis, true);
                   }
                   else if ($chapo == 1) {
                       $link .= $article->getText();
                   }
                $link .= _close('p');
            $link .= _close('div');

            echo _link($article)->set('.thumbnail')->text($link);

            echo _close('li');
            $i++;
        } 
    echo _close('ul');
    if ((isset($lien)) AND ($lien != '')) { 
            echo _link('sidActuArticle/list')->text($lien)->set('.btn');
        
        }
    } // sinon on affiche rien
}
