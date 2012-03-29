<?php
// $vars = $pageCabinet, $titlePage, $lien
$html = '';
use_helper('Date');
	//affichage du contenu
//	$pageCabinetOpts = array(
//					'container' => 'article',
//					'name' => $titlePage,
//					'description' => $pageCabinet->getResume(),
//					'image' => $pageCabinet->getImage(),
//					'dateCreated' => $pageCabinet->created_at,
//					'isDateMeta' => true,
//					'articleBody' => $pageCabinet->getText()
//					);
//	
//	$html.= get_partial('global/schema/Thing/CreativeWork/Article', $pageCabinetOpts);
//	
//	//création d'un tableau de liens à afficher
//	$elements = array();
//	$elements[] = array('title' => $lien, 'linkUrl' => 'main/contact');
//	
//	$html.= get_partial('global/navigationWrapper', array(
//													'placement' => 'bottom',
//													'elements' => $elements
//													));
if ($titreBloc != '') {
            echo _tag('h2.title',$titreBloc);
        }
echo _open('article', array('class' => 'itemscope Article', 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope'));  
    
    echo _open('header', array('class' => 'contentHeader'));
        
        if ($pageCabinet->getImage()->checkFileExists() == true) {
            echo _open('div', array('class' => 'imageFullWrapper'));
                if($width != null) {echo  _media($pageCabinet->getImage())->width($width)->set('.image itemprop="image"')->alt($pageCabinet->getTitle());}
            echo _close('div');
        }
        echo _tag('h1', array('class' => 'title itemprop name', 'itemprop' => "name"), $pageCabinet->getTitle());
        echo _tag('meta', array('content' => $pageCabinet->createdAt, 'itemprop' => 'datePublished'));
        echo _tag('span', array('class' => 'teaser itemprop description', 'itemprop' => 'description'), $pageCabinet->getResume());
    echo _close('header');
    echo _tag('section', array('class' => 'contentBody', 'itemprop' => 'articleBody'), $pageCabinet->getText());
    echo _open('footer', array('class' => 'contentFooter'));
        echo _open('div', array('class' => 'navigationWrapper navigationBottom'));
            echo _open('ul', array('class' => 'elements'));
                echo _tag('li', array('class' => 'element first last'), 
                        _link('main/contact')->text($lien)
                        );
            echo _close('ul');
        echo _close('div');
    echo _close('footer');
echo _close('article');