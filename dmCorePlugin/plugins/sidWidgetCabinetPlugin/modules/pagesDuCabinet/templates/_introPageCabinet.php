<?php
// vars = $pageCabinet, $length ,$lien, $titreBloc, $width, $height, $withImage

//Récupération des variables
$ellipsis = _tag('span.ellipsis', sfConfig::get('app_vars-partial_ellipsis'));
$i = 1;
$i_max = count($pageCabinets);

if (count($pageCabinets)) { // si nous avons des actu articles
	
    echo _tag('h4.title',$titreBloc);
    echo _open('ul', array('class' => 'elements'));
	foreach ($pageCabinets as $pageCabinet) {  
        $link = '';  
        
        //définition des options du li
        $ctnOpts = array('class' => array('element', 'itemscope', 'Article'), 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope');
        if($i == 1)         $ctnOpts['class'][] = 'first';
        if($i >= $i_max)    $ctnOpts['class'][] = 'last';
        
        echo _open('li', $ctnOpts);

        $link = '';

        if(($withImage == true) && ($pageCabinet->getImage()->checkFileExists() == true)){ 
            $link .= _open('span', array('class' => 'imageWrapper'));
            $link .= _media($pageCabinet->getImage())->width($width)->set('.image itemprop="image"')->alt($pageCabinet->getTitle());
            $link .= _close('span');
        };
        $link .= _open('span' , array('class' => 'wrapper'));
            $link .= _open('span' , array('class' => 'subWrapper'));
                if($titreBloc != $pageCabinet->getTitle()){
                    $link .= _tag('span', array('class' => array('title', 'itemprop', 'name'), 'itemprop' => 'name') , $pageCabinet->getTitle());
                };
                $link .= _tag('meta' , array('content' => $pageCabinet->createdAt, 'itemprop' => 'datePublished'));
            $link .= _close('span');
            $link .= _open('span', array('class' => array('teaser', 'itemprop', 'description') , 'itemprop' => 'description'));
                $link .= stringTools::str_truncate($pageCabinet->getResume(), $length, $ellipsis, true);
            $link .= _close('span');
        $link .= _close('span');

        echo _link($pageCabinet)->set('.link_box')->text($link);

        echo _close('li');
    } 
echo _close('ul');

 if ((isset($lien)) AND ($lien != '')) { 
        echo _open('div', array('class' => 'navigationWrapper navigationBottom'));
            echo _open('ul', array('class' => 'elements'));
                echo _tag('li', array('class' => 'element first last'), 
                     _link('pageCabinet/list')->text($lien)
                        );
            echo _close('ul');
        echo _close('div');
    }

} ?>