<?php

//$vars =  $articles, $nbArticles, $titreBloc, $lien, $length, $chapo, $width, $height, $withImage, $header, $constanteActuCabinet
$i = 1;
$i_max = count($articles);
$class = '';
if (count($articles)) { // si nous avons des actu articles
    //gestion affichage du titre
    echo _tag('h4.title', $titreBloc);
    if (count($articles) == 1 && ($redirect == true)) {
        header("Location: " . $header);
        exit;
    } else {

        echo _open('ul', array('class' => 'elements'));

        foreach ($articles as $article) {
            $link = '';
            // class first ou last pour listing
            if ($i == 1) {
                $class = 'first';
                if ($i == $i_max)
                    $class = 'first last';
            }
            elseif ($i == $i_max)
                $class = 'last';
            else
                $class = '';

            echo _open('li', array('class' => 'element itemscope Article ' . $class, 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope'));



            if ($withImage == true) {
                if (($article->getImage()->checkFileExists() == true) and ($i <= sfConfig::get('app_nb-image'))) {
                    $link .= _open('span', array('class' => 'imageWrapper'));
                    $link .= _media($article->getImage())->width($width)->set('.image itemprop="image"')->alt($article->getTitle());
                    $link .= _close('span');
                }
            };
            $link .= _open('span', array('class' => 'wrapper'));
            _open('span', array('class' => 'subWrapper'));

            if ($titreBloc != $article->getTitle()) {
                $link .= _tag('span', array('class' => 'title itemprop name', 'itemprop' => 'name'), $article->getTitle());
            };
            $link .= _tag('meta', array('content' => $article->createdAt, 'itemprop' => 'datePublished'));
            $link .= _close('span');
            $link .= _open('span', array('class' => 'teaser itemprop description', 'itemprop' => 'description'));
            if ($chapo == 0) {
                $link .= stringTools::str_truncate($article->getResume(), $length, '(...)', true);
            } else if ($chapo == 1) {
                $link .= $article->getText();
            }
            $link .= _close('span');
            $link .= _close('span');

            echo _link($article)->set('.link_box')->text($link);
            $i++;
            echo _close('li');
        }
        echo _close('ul');
    }
}
else {
    if($this->context->getPage()->getAction() != 'show'){    
    echo _tag('h4.title',$titreBloc);
	// sinon on affiche la constante de la page concernée
        echo $constanteActuCabinet;
    }
}
