<?php
// vars : $missions, $titreBloc

//$html = '';
////titre du contenu
//if($titreBloc != null) $html = get_partial('global/titleWidget', array('title' => $titreBloc, 'isContainer' => true));
//
//// si nous avons des actu articles
//if(count($missions)) {
//	//affichage du contenu
//	$missionOpts = array(
//					'container' => 'article',
//					'name' => $missions->getTitle(),
//					'dateCreated' => $missions->created_at,
//					'isDateMeta' => true,
//					'description' => $missions->getResume(),
//					'articleBody' => $missions->getText(),
//					'articleSection' => $titreBloc
//					);
//	$html.= get_partial('global/schema/Thing/CreativeWork/Article', $missionOpts);
//	
//}else{
//	// sinon on affiche rien
//	$html.= get_partial('global/schema/Thing/CreativeWork/Article', array('container' => 'article', 'articleBody' => '{{mission}}'));
//}
//
////affichage html de sortie
//echo $html;
if(dmConfig::get('site_theme_version') == 'v1'){
    echo _tag('h2.title',$titreBloc);
    echo _open('article', array('class' => 'itemscope Article', 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope'));
        echo _open('header', array('class' => 'contentHeader'));
            echo _tag('h1', array('class' => 'title itemprop name', 'itemprop' => 'name'), $missions->getTitle());
            if (($withImage == true) && ($missions->getImage()->checkFileExists() == true)) {
                        echo _open('div', array('class' => 'imageFullWrapper'));
                            if($width != null) {echo  _media($missions->getImage())->width($width)->set('.image itemprop="image"')->alt($missions->getTitle());}
                        echo _close('div');
                    }
            echo _tag('meta', array('content' => $titreBloc, 'itemprop' => 'articleSection'));
            //echo _tag('span', array('class' => 'teaser itemprop description'), $missions->getResume());
            echo _tag('meta', array('content' => $missions->createdAt, 'itemprop' => 'datePublished'));
        echo _close ('header');
        echo _tag('section', array('class' => 'contentBody', 'itemprop' => 'articleBody'), $missions->getText());
echo _close('article');
}
elseif (dmConfig::get('site_theme_version') == 'v2') {
    echo _tag('h2',$titreBloc);
    echo _open('article', array('class' => 'itemscope Article', 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope'));
        echo _open('header', array('class' => 'contentHeader'));
            echo _tag('h1', array('class' => 'itemprop name', 'itemprop' => 'name'), $missions->getTitle());
            if (($withImage == true) && ($missions->getImage()->checkFileExists() == true)) {
                            if($width != null) {echo  _media($missions->getImage())->width($width)->set('itemprop="image"')->alt($missions->getTitle());}
            }
            echo _tag('meta', array('content' => $titreBloc, 'itemprop' => 'articleSection'));
            //echo _tag('span', array('class' => 'teaser itemprop description'), $missions->getResume());
            echo _tag('meta', array('content' => $missions->createdAt, 'itemprop' => 'datePublished'));
        echo _close ('header');
        echo _tag('section', array('class' => 'contentBody', 'itemprop' => 'articleBody'), $missions->getText());
    echo _close('article');
}
