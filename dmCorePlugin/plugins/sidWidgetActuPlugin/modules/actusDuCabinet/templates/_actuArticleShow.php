<?php
// vars : $articles, $titreBloc, $widthImage, $heightImage, $withImage
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
                        if($width != null) {echo  _media($article->getImage())->width($width)->set('.image itemprop="image"')->alt($article->getTitle());}
                    echo _close('div');
                }
                echo _tag('h1', array('class' => 'title itemprop name', 'itemprop' => "name"), $article->getTitle());
                echo _tag('meta', array('content' => $titreBloc, 'itemprop' => 'articleSection'));
                echo _tag('span', array('class' => 'teaser itemprop description', 'itemprop' => 'description'), $article->getResume());
                echo _open('span.date');
                    echo __('published on').' ';
                    echo _tag('time', array('class' => 'datePublished', 'itemprop' => 'datePublished', 'pubdate' => 'pubdate', 'datetime' => format_date($article->createdAt, 'I')), format_date($article->createdAt, 'D' ));
                echo _close('span');    
            echo _close('header');
            echo _tag('section', array('class' => 'contentBody', 'itemprop' => 'articleBody'), $article->getText());
        echo _close('article');
    }


}else{
   
	echo '{{actualites_du_cabinet}}';
}
