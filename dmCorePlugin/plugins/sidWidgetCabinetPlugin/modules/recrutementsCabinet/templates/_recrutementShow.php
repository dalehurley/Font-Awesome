<?php
// vars : $recrutements, $titreBloc, $width, $withImage

/*$html = '';
//titre du contenu
if($titreBloc != null) $html = get_partial('global/titleWidget', array('title' => $titreBloc, 'isContainer' => true));

if(count($recrutements)){
	//affichage du contenu
	$recrutementOpts = array(
						'container' => 'article',
						'name' => $recrutements->getTitle(),
						'dateCreated' => $recrutements->created_at,
						'isDateMeta' => true,
						'articleBody' => $recrutements->getText(),
						'articleSection' => $titreBloc
						);
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', $recrutementOpts);
	
}else{
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', array('container' => 'article', 'articleBody' => '{{recrutement}}'));
}

//affichage html en sortie
echo $html;*/

if (dmConfig::get('site_theme_version') == 'v1'){
echo _tag('h2.title',$titreBloc);
echo _open('article', array('class' => 'itemscope Article', 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope'));
    echo _open('header', array('class' => 'contentHeader'));
        echo _tag('h1', array('class' => 'title itemprop name', 'itemprop' => 'name'), $recrutements->getTitle());
        if (($withImage == true) && ($recrutements->getImage()->checkFileExists() == true)) {
                    echo _open('div', array('class' => 'imageFullWrapper'));
                        if($width != null) {echo  _media($recrutements->getImage())->width($width)->set('.image itemprop="image"')->alt($recrutements->getTitle());}
                    echo _close('div');
                }
        echo _tag('meta', array('content' => $titreBloc, 'itemprop' => 'articleSection'));
        echo _tag('meta', array('content' => $recrutements->createdAt, 'itemprop' => 'datePublished'));
    echo _close ('header');
    echo _tag('section', array('class' => 'contentBody', 'itemprop' => 'articleBody'), $recrutements->getText());
    if($recrutements->getFiles()->checkFileExists() == true){
            echo _open('footer', array('class' => 'contentFooter'));
                echo _open('div', array('class' => 'fileWrapper'));
                    echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                    echo _link($recrutements->getFiles());
                echo _close('div');
            echo _close('footer');
        }
echo _close('article');
}
elseif (dmConfig::get('site_theme_version') == 'v2'){
echo _tag('h2',$titreBloc);
echo _open('article', array('class' => 'itemscope Article', 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope'));
    echo _open('header', array('class' => 'contentHeader'));
        echo _tag('h1', array('class' => 'itemprop name', 'itemprop' => 'name'), $recrutements->getTitle());
        if (($withImage == true) && ($recrutements->getImage()->checkFileExists() == true)) {
                        if($width != null) {echo  _media($recrutements->getImage())->width($width)->set('itemprop="image"')->alt($recrutements->getTitle());}
                }
        echo _tag('meta', array('content' => $titreBloc, 'itemprop' => 'articleSection'));
        echo _tag('meta', array('content' => $recrutements->createdAt, 'itemprop' => 'datePublished'));
    echo _close ('header');
    echo _tag('section', array('class' => 'contentBody', 'itemprop' => 'articleBody'), _tag('p',$recrutements->getText()));
    echo _tag('hr');
    if($recrutements->getFiles()->checkFileExists() == true){
        echo _open('footer', array('class' => 'contentFooter'));
            echo _tag('h5', __('Download file, click the link below'));
            if($recrutements->getTitleFile() != NULL){
            echo _link($recrutements->getFiles())->text('<i class="icon-large icon-download-alt"></i>&nbsp;'.$recrutements->getTitleFile())->set('.btn');
            }
            else echo _link($recrutements->getFiles())->text('<i class="icon-large icon-download-alt"></i>&nbsp;'.$recrutements->getFiles()->getFile())->set('.btn');
        echo _close('footer');
        echo _tag('hr');
    }
echo _close('article');
}