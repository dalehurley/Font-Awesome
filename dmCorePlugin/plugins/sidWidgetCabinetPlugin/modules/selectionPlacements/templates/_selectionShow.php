<?php
// vars : $selections, $titreBloc, $width, $withImage

echo _tag('h2.title',$titreBloc);
echo _open('article', array('class' => 'itemscope Article', 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope'));
    echo _open('header', array('class' => 'contentHeader'));
        echo _tag('h1', array('class' => 'title itemprop name', 'itemprop' => 'name'), $selections->getTitle());
        if (($withImage == true) && ($selections->getImage()->checkFileExists() == true)) {
                    echo _open('div', array('class' => 'imageFullWrapper'));
                        if($width != null) {echo  _media($selections->getImage())->width($width)->set('.image itemprop="image"');}
                    echo _close('div');
                }
        echo _tag('meta', array('content' => $titreBloc, 'itemprop' => 'articleSection'));
        echo _tag('meta', array('content' => $selections->createdAt, 'itemprop' => 'datePublished'));
    echo _close ('header');
    echo _tag('section', array('class' => 'contentBody', 'itemprop' => 'articleBody'), $selections->getText());
    if($selections->getFiles()->checkFileExists() == true){
            echo _open('footer', array('class' => 'contentFooter'));
                echo _open('div', array('class' => 'fileWrapper'));
                    echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                    echo _link($selections->getFiles());
                echo _close('div');
            echo _close('footer');
        }
echo _close('article');