<?php // Vars: $sidAddedPagesLevel2, $theme

//chargement de l'helper pour la date
use_helper('Date');
//titre du contenu
if(($sidAddedPagesLevel2->getResume() != NULL) || $sidAddedPagesLevel2->getText() != NULL){       
        echo _tag('h1', array('class' => 'title itemprop name', 'itemprop' => "name"), ucfirst($sidAddedPagesLevel2->getTitle()));

        echo _open('article', array('class' => 'itemscope Article', 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope'));  
            echo _open('header', array('class' => 'contentHeader'));
                if ($sidAddedPagesLevel2->getImage()->checkFileExists() == true) {
                    echo _open('div', array('class' => 'imageFullWrapper'));
                       echo  _media($sidAddedPagesLevel2->getImage())
                                ->size($theme['width-image'],$theme['height-image'])
                                ->method('scale')
                                ->set('.image itemprop="image"')
                                ->alt($sidAddedPagesLevel2->getTitle());
                    echo _close('div');
                }
//                echo _tag('h1', array('class' => 'title itemprop name', 'itemprop' => "name"), $sidAddedPagesLevel2->getTitle());
//                echo _tag('meta', array('content' => $sidAddedPagesLevel2->getTitle(), 'itemprop' => 'articleSection'));
                echo _tag('span', array('class' => 'teaser itemprop description', 'itemprop' => 'description'), $sidAddedPagesLevel2->getResume());


                echo _open('span.date');
                    echo __('published on').' ';
                    echo _tag('time', array('class' => 'datePublished', 'itemprop' => 'datePublished', 'pubdate' => 'pubdate', 'datetime' => format_date($sidAddedPagesLevel2->createdAt, 'I')), format_date($sidAddedPagesLevel2->createdAt, 'D' ));
                echo _close('span'); 


            echo _close('header');
            echo _tag('section', array('class' => 'contentBody', 'itemprop' => 'articleBody'), $sidAddedPagesLevel2->getText());
            if($sidAddedPagesLevel2->getFiles1()->checkFileExists() == true){
            echo _open('footer', array('class' => 'contentFooter'));
                echo _open('div', array('class' => 'fileWrapper'));
                    echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                    if($sidAddedPagesLevel2->getTitleFile1() != NULL){
                    echo _link($sidAddedPagesLevel2->getFiles1())->text($sidAddedPagesLevel2->getTitleFile1());
                }
                else echo _link($sidAddedPagesLevel2->getFiles1());
                echo _close('div');
            echo _close('footer');
            }
            if($sidAddedPagesLevel2->getFiles2()->checkFileExists() == true){
            echo _open('footer', array('class' => 'contentFooter'));
                echo _open('div', array('class' => 'fileWrapper'));
                    echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                    if($sidAddedPagesLevel2->getTitleFile2() != NULL){
                    echo _link($sidAddedPagesLevel2->getFiles2())->text($sidAddedPagesLevel2->getTitleFile2());
                }
                else echo _link($sidAddedPagesLevel2->getFiles2());
                echo _close('div');
            echo _close('footer');
            }
            if($sidAddedPagesLevel2->getFiles3()->checkFileExists() == true){
            echo _open('footer', array('class' => 'contentFooter'));
                echo _open('div', array('class' => 'fileWrapper'));
                    echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                    if($sidAddedPagesLevel2->getTitleFile3() != NULL){
                    echo _link($sidAddedPagesLevel2->getFiles3())->text($sidAddedPagesLevel2->getTitleFile3());
                }
                else echo _link($sidAddedPagesLevel2->getFiles3());
                echo _close('div');
            echo _close('footer');
            }
            if($sidAddedPagesLevel2->getFiles4()->checkFileExists() == true){
            echo _open('footer', array('class' => 'contentFooter'));
                echo _open('div', array('class' => 'fileWrapper'));
                    echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                    if($sidAddedPagesLevel2->getTitleFile4() != NULL){
                    echo _link($sidAddedPagesLevel2->getFiles4())->text($sidAddedPagesLevel2->getTitleFile4());
                }
                else echo _link($sidAddedPagesLevel2->getFiles4());
                echo _close('div');
            echo _close('footer');
            }
            if($sidAddedPagesLevel2->getFiles5()->checkFileExists() == true){
            echo _open('footer', array('class' => 'contentFooter'));
                echo _open('div', array('class' => 'fileWrapper'));
                    echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                    if($sidAddedPagesLevel2->getTitleFile5() != NULL){
                    echo _link($sidAddedPagesLevel2->getFiles5())->text($sidAddedPagesLevel2->getTitleFile5());
                }
                else echo _link($sidAddedPagesLevel2->getFiles5());
                echo _close('div');
            echo _close('footer');
            }
            echo _close('article');
}
