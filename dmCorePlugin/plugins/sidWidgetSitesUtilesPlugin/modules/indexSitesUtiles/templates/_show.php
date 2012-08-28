<?php // Vars: $indexSitesUtiles
//titre du contenu
/*
//affichage du contenu
$articleOpts = array('articleBody' => $indexSitesUtiles->description);

$html.= get_partial('global/schema/Thing/CreativeWork/Article', $articleOpts);

//affichage html en sortie
echo $html;
*/

if(count($indexSitesUtiles)){
	if (dmConfig::get('site_theme_version') == 'v1'){
		echo _tag('h2.title',$indexSitesUtiles->getTitle());
		echo _tag('section', array('class' => 'contentBody', 'itemprop' => 'articleBody'), $indexSitesUtiles->getDescription());
	}
	elseif (dmConfig::get('site_theme_version') == 'v2'){
	echo _tag('h2',$indexSitesUtiles->getTitle());
	echo _open('article', array('class' => 'itemscope Article', 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope'));
	    echo _open('header', array('class' => 'contentHeader'));
	        //echo _tag('h1', array('class' => 'itemprop name', 'itemprop' => 'name'), $indexSitesUtiles->getTitle());
	        if ($indexSitesUtiles->getImage()->checkFileExists() == true) {
	                        if($width != null) {echo  _media($indexSitesUtiles->getImage())
                                        ->size($theme['width-image'],$theme['height-image'])
                                        ->method('scale')
                                        ->set('itemprop="image"')
                                        ->alt($indexSitesUtiles->getTitle());}
	                }
	        echo _tag('meta', array('content' => $indexSitesUtiles->getTitle(), 'itemprop' => 'articleSection'));
	        echo _tag('meta', array('content' => $indexSitesUtiles->createdAt, 'itemprop' => 'datePublished'));
	    echo _close ('header');
	    echo _tag('section', array('class' => 'contentBody', 'itemprop' => 'articleBody'), _tag('p',$indexSitesUtiles->getDescription()));
	    echo _tag('hr');
	    if($indexSitesUtiles->getFiles()->checkFileExists() == true){
	        echo _open('footer', array('class' => 'contentFooter'));
	            echo _tag('h5', __('Download file, click the link below'));
	            if($indexSitesUtiles->getTitleFile() != NULL){
	            echo _link($indexSitesUtiles->getFiles())->text('<i class="icon-large icon-download-alt"></i>&nbsp;'.$indexSitesUtiles->getTitleFile());
	            }
	            else echo _link($indexSitesUtiles->getFiles())->text('<i class="icon-large icon-download-alt"></i>&nbsp;'.$indexSitesUtiles->getFiles()->getFile());
	        echo _close('footer');
	        echo _tag('hr');
	    }
	echo _close('article');
	}
}