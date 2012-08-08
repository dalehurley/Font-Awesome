<?php
// vars : $articles, $titreBloc, $widthImage, $heightImage, $withImage
if (dmConfig::get('site_theme_version') == 'v1'){
    //chargement de l'helper pour la date
    use_helper('Date');

    //titre du contenu
    echo _tag('h2.title', $titreBloc);

    if(count($articles)){

        foreach ($articles as $article){
    //        $image = '';
    	//affichage du contenu
    //	$articleOpts = array(
    //						'container' => 'article',
    //						'name' => $articles->getTitle(),
    //						'description' => $articles->getResume(),
    //						'image' => $articles->getImage(),
    //						'dateCreated' => $articles->created_at,
    //						'dateModified' => $articles->updated_at,
    //						'articleBody' => $articles->getText(),
    //						'articleSection' => $titreBloc
    //					);
    //	
    //	//ajout des options de fichiers
    //	if($articles->getFiles() != '') $articleOpts['uploadFile'] = $articles->getFiles();
    //	if($articles->getTitleFile() != '') $articleOpts['uploadFileTitle'] = $articles->getTitleFile();
    //	
    //	$html.= get_partial('global/schema/Thing/CreativeWork/Article', $articleOpts);
       

            echo _open('article', array('class' => 'itemscope Article', 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope'));  
                echo _open('header', array('class' => 'contentHeader'));
                    if (($withImage == true) && ($article->getImage()->checkFileExists() == true)) {
                        echo _open('div', array('class' => 'imageFullWrapper'));
                            if($height != null) {echo  _media($article->getImage())->height($height)->method('scale')->set('.image itemprop="image"')->alt($article->getTitle());}
                        echo _close('div');
                    }
                    echo _tag('h1', array('class' => 'title itemprop name', 'itemprop' => "name"), $article->getTitle());
                    echo _tag('meta', array('content' => $titreBloc, 'itemprop' => 'articleSection'));
                    echo _tag('span', array('class' => 'teaser itemprop description', 'itemprop' => 'description'), $article->getResume());

    if ($withPublishedDate){
                    echo _open('span.date');
                        echo __('published on').' ';
                        echo _tag('time', array('class' => 'datePublished', 'itemprop' => 'datePublished', 'pubdate' => 'pubdate', 'datetime' => format_date($article->createdAt, 'I')), format_date($article->createdAt, 'D' ));
                    echo _close('span'); 
    }

                echo _close('header');
                echo _tag('section', array('class' => 'contentBody', 'itemprop' => 'articleBody'), $article->getText());
                if($article->getFiles()->checkFileExists() == true){
                echo _open('footer', array('class' => 'contentFooter'));
                    echo _open('div', array('class' => 'fileWrapper'));
                        echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                        if($article->getTitleFile() != NULL){
                        echo _link($article->getFiles())->text($article->getTitleFile());
                    }
                    else echo _link($article->getFiles());
                    echo _close('div');
                echo _close('footer');
            }
            echo _close('article');
        }


    }else{
    	echo '{{actualites_du_cabinet}}';
    }
}
elseif (dmConfig::get('site_theme_version') == 'v2'){
    //chargement de l'helper pour la date
    use_helper('Date');

    //titre du contenu
    echo _tag('h2', $titreBloc);

    if(count($articles)){

        foreach ($articles as $article){
            echo _open('article', array('class' => 'itemscope Article', 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope'));  
                echo _open('header', array('class' => 'contentHeader'));
                    if (($withImage == true) && ($article->getImage()->checkFileExists() == true)) {
                            if($height != null) {echo  _media($article->getImage())->height($height)->method('scale')->set('itemprop="image"')->alt($article->getTitle());}
                    }
                    echo _tag('h1', array('class' => 'itemprop name', 'itemprop' => "name"), $article->getTitle());
                    echo _tag('meta', array('content' => $titreBloc, 'itemprop' => 'articleSection'));
                    echo _tag('p', array('class' => 'itemprop description', 'itemprop' => 'description'), _tag('strong',$article->getResume()));

                    if ($withPublishedDate){
                        echo __('published on').' ';
                        echo _tag('time', array('class' => 'datePublished', 'itemprop' => 'datePublished', 'pubdate' => 'pubdate', 'datetime' => format_date($article->createdAt, 'I')), format_date($article->createdAt, 'D' ));
                    }
                echo _close('header');
                echo _tag('section', array('class' => 'contentBody', 'itemprop' => 'articleBody'), $article->getText());
                if($article->getFiles()->checkFileExists() == true){
                echo _open('footer', array('class' => 'contentFooter'));
                    echo _open('div', array('class' => 'fileWrapper'));
                        echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                        if($article->getTitleFile() != NULL){
                        echo _link($article->getFiles())->text('<i class="icon-download-alt"></i>&nbsp;'.$article->getTitleFile())->set('.btn');
                    }
                    else echo _link($article->getFiles())->text('<i class="icon-download-alt"></i>&nbsp;'.$article->getFiles()->getFile())->set('.btn');;
                    echo _close('div');
                echo _close('footer');
            }
            echo _close('article');
        }


    }else{
        echo '{{actualites_du_cabinet}}';
    }
}