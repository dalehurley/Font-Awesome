<?php
// vars = $pageCabinets, $length ,$lien, $titreBloc, $width, $height, $withImage, $header, $redirect
if(dmConfig::get('site-theme-version') == 'v1'){
    //Récupération des variables
    $ellipsis = _tag('span.ellipsis', sfConfig::get('app_vars-partial_ellipsis'));
    $i = 1;
    $i_max = count($pageCabinets);

    if (count($pageCabinets)) { // si nous avons des actu articles
        if (count($pageCabinets) == 1 && ($redirect == true)) {
            header("Location: " . $header);
            exit;
        } else {

            echo _tag('h4.title', $titreBloc);
            echo _open('ul', array('class' => 'elements'));
                foreach ($pageCabinets as $pageCabinet) {
                    $link = '';

                    //définition des options du li
                    $ctnOpts = array('class' => array('element', 'itemscope', 'Article'), 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope');
                    if($i == 1)         $ctnOpts['class'][] = 'first';
                    if($i >= $i_max)    $ctnOpts['class'][] = 'last';

                    echo _open('li', $ctnOpts);

                    $link = '';

                    if (($withImage == true) && ($pageCabinet->getImage()->checkFileExists() == true)) {
                        $link .= _open('span', array('class' => 'imageWrapper'));
                            $link .= _media($pageCabinet->getImage())->width($width)->set('.image itemprop="image"')->alt($pageCabinet->getTitle());
                        $link .= _close('span');
                    };
                    $link .= _open('span', array('class' => 'wrapper'));
                        $link .= _open('span', array('class' => 'subWrapper'));
                            if ($titreBloc != $pageCabinet->getTitle()) {
                                $link .= _tag('span', array('class' => array('title', 'itemprop', 'name'), 'itemprop' => 'name'), $pageCabinet->getTitle());
                            };
                            $link .= _tag('meta', array('content' => $pageCabinet->createdAt, 'itemprop' => 'datePublished'));
                        $link .= _close('span');
                        $link .= _open('span', array('class' => array('teaser', 'itemprop', 'description') , 'itemprop' => 'description'));
                            $link .= stringTools::str_truncate($pageCabinet->getResume(), $length, $ellipsis, true);
                        $link .= _close('span');
                    $link .= _close('span');
                    
                    echo _link($pageCabinet)->set('.link_box')->text($link);

                    echo _close('li');
                    $i++;
                }
            echo _close('ul');
        }
    }
    else {
        echo debugTools::infoDebug(array(
                'if no page cabinet created in admin:' => 'Please add a page cabinet',
                'if there is just one page cabinet created in admin (this page):' => 'Nothing to do'
            ), 'info');
     //    if($this->context->getPage()->getAction() != 'show'){    
     //    echo _tag('h4.title',$titreBloc);
    	// // sinon on affiche la constante de la page concernée
     //        echo $constanteActuCabinet;
     //    }
    }
}
elseif (dmConfig::get('site_theme_version') == 'v2'){
    //Récupération des variables
    $ellipsis = _tag('span.ellipsis', sfConfig::get('app_vars-partial_ellipsis'));
    $i = 1;
    $i_max = count($pageCabinets);

    if (count($pageCabinets)) { // si nous avons des actu articles
        if (count($pageCabinets) == 1 && ($redirect == true)) {
            header("Location: " . $header);
            exit;
        } else {

            echo _tag('h3', $titreBloc);
            echo _open('ul', array('class' => 'thumbnails'));
                foreach ($pageCabinets as $pageCabinet) {
                    $link = '';

                    //définition des options du li
                    $ctnOpts = array('class' => array('itemscope', 'Article'), 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope');
                    if($i == 1)         $ctnOpts['class'][] = 'first';
                    if($i >= $i_max)    $ctnOpts['class'][] = 'last';

                    echo _open('li', $ctnOpts);

                    $link = '';

                    if (($withImage == true) && ($pageCabinet->getImage()->checkFileExists() == true)) {
                            $link .= _media($pageCabinet->getImage())->width($width)->set('itemprop="image"')->alt($pageCabinet->getTitle());
                    };

                    if ($titreBloc != $pageCabinet->getTitle()) {
                        $link .= _tag('h4', array('class' => array('title', 'itemprop', 'name'), 'itemprop' => 'name'), $pageCabinet->getTitle());
                    };
 
                    $link .= _open('div', array('class' => 'caption'));
                            $link .= _tag('meta', array('content' => $pageCabinet->createdAt, 'itemprop' => 'datePublished'));
                        $link .= _open('p', array('class' => array('caption', 'itemprop', 'description') , 'itemprop' => 'description'));
                            $link .= stringTools::str_truncate($pageCabinet->getResume(), $length, $ellipsis, true);
                        $link .= _close('p');
                    $link .= _close('div');
                    
                    echo _link($pageCabinet)->set('.thumbnail')->text($link);

                    echo _close('li');
                    $i++;
                }
            echo _close('ul');
        }
    }
    else {
        echo debugTools::infoDebug(array(
                'if no page cabinet created in admin:' => 'Please add a page cabinet',
                'if there is just one page cabinet created in admin (this page):' => 'Nothing to do'
            ), 'info');
     //    if($this->context->getPage()->getAction() != 'show'){    
     //    echo _tag('h4.title',$titreBloc);
        // // sinon on affiche la constante de la page concernée
     //        echo $constanteActuCabinet;
     //    }
    }
}