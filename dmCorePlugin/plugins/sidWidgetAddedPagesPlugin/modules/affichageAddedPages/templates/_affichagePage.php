<?php // Vars: $sidAddedPages, $theme, $withImage, $withDate, $withResume, $widthImage, $heightImage
//chargement de l'helper pour la date
use_helper('Date');
//titre du contenu
if(($sidAddedPages->getResume() != NULL) || $sidAddedPages->getText() != NULL){       
        echo _tag('h1', array('class' => 'title itemprop name', 'itemprop' => "name"), ucfirst($sidAddedPages->getTitle()));

        echo _open('article', array('class' => 'itemscope Article', 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope'));  
            echo _open('header', array('class' => 'contentHeader'));
                if ($withImage == true && $sidAddedPages->getImage()->checkFileExists() == true) {
                    echo _open('div', array('class' => 'imageFullWrapper'));
                       echo  _media($sidAddedPages->getImage())
                                ->size($widthImage,$heightImage)
                                ->method('scale')
                                ->set('.image itemprop="image"')
                                ->alt($sidAddedPages->getTitle());
                    echo _close('div');
                }
//                echo _tag('h1', array('class' => 'title itemprop name', 'itemprop' => "name"), $sidAddedPages->getTitle());
//                echo _tag('meta', array('content' => $sidAddedPages->getTitle(), 'itemprop' => 'articleSection'));
                if($withResume == true){
                echo _tag('span', array('class' => 'teaser itemprop description', 'itemprop' => 'description'), $sidAddedPages->getResume());
                }
                if($withDate == true){
                echo _open('span.date');
                    echo __('published on').' ';
                    echo _tag('time', array('class' => 'datePublished', 'itemprop' => 'datePublished', 'pubdate' => 'pubdate', 'datetime' => format_date($sidAddedPages->createdAt, 'I')), format_date($sidAddedPages->createdAt, 'D' ));
                echo _close('span'); 
                }

            echo _close('header');
            echo _tag('section', array('class' => 'contentBody', 'itemprop' => 'articleBody'), $sidAddedPages->getText());
            if($sidAddedPages->getFiles1()->checkFileExists() == true){
            echo _open('footer', array('class' => 'contentFooter'));
                echo _open('div', array('class' => 'fileWrapper'));
                    echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                    if($sidAddedPages->getTitleFile1() != NULL){
                    echo _link($sidAddedPages->getFiles1())->text($sidAddedPages->getTitleFile1());
                }
                else echo _link($sidAddedPages->getFiles1());
                echo _close('div');
            echo _close('footer');
            }
            if($sidAddedPages->getFiles2()->checkFileExists() == true){
            echo _open('footer', array('class' => 'contentFooter'));
                echo _open('div', array('class' => 'fileWrapper'));
                    echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                    if($sidAddedPages->getTitleFile2() != NULL){
                    echo _link($sidAddedPages->getFiles2())->text($sidAddedPages->getTitleFile2());
                }
                else echo _link($sidAddedPages->getFiles2());
                echo _close('div');
            echo _close('footer');
            }
            if($sidAddedPages->getFiles3()->checkFileExists() == true){
            echo _open('footer', array('class' => 'contentFooter'));
                echo _open('div', array('class' => 'fileWrapper'));
                    echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                    if($sidAddedPages->getTitleFile3() != NULL){
                    echo _link($sidAddedPages->getFiles3())->text($sidAddedPages->getTitleFile3());
                }
                else echo _link($sidAddedPages->getFiles3());
                echo _close('div');
            echo _close('footer');
            }
            if($sidAddedPages->getFiles4()->checkFileExists() == true){
            echo _open('footer', array('class' => 'contentFooter'));
                echo _open('div', array('class' => 'fileWrapper'));
                    echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                    if($sidAddedPages->getTitleFile4() != NULL){
                    echo _link($sidAddedPages->getFiles4())->text($sidAddedPages->getTitleFile4());
                }
                else echo _link($sidAddedPages->getFiles4());
                echo _close('div');
            echo _close('footer');
            }
            if($sidAddedPages->getFiles5()->checkFileExists() == true){
            echo _open('footer', array('class' => 'contentFooter'));
                echo _open('div', array('class' => 'fileWrapper'));
                    echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                    if($sidAddedPages->getTitleFile5() != NULL){
                    echo _link($sidAddedPages->getFiles5())->text($sidAddedPages->getTitleFile5());
                }
                else echo _link($sidAddedPages->getFiles5());
                echo _close('div');
            echo _close('footer');
            }
            echo _close('article');
}
