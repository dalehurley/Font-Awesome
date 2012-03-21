<?php

// vars = $pageCabinets, $length ,$lien, $titreBloc, $width, $height, $withImage, $header, $redirect
$i = 1;
$position = '';
$i_max = '';
$i_max = count($pageCabinets);
if (count($pageCabinets)) { // si nous avons des actu articles
    if (count($pageCabinets) == 1 && ($redirect == true)) {
        header("Location: " . $header);
        exit;
    } else {

        echo _tag('h4.title', $titreBloc);
        echo _open('ul', array('class' => 'elements'));
            foreach ($pageCabinets as $pageCabinet) {
                $link = '';

                switch ($i) {
                    case 1:
                        if ($i_max == 1)
                            $position = 'first last';
                        else
                            $position = 'first';
                        break;
                    case $i_max : $position = 'last';
                        break;
                    default : $position = '';
                }


                echo _open('li', array('class' => 'element itemscope Article ' . $position, 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope'));


                $link = '';

                if (($withImage == true) && ($pageCabinet->getImage()->checkFileExists() == true)) {
                    $link .= _open('span', array('class' => 'imageWrapper'));
                        $link .= _media($pageCabinet->getImage())->width($width)->set('.image itemprop="image"')->alt($pageCabinet->getTitle());
                    $link .= _close('span');
                };
                $link .= _open('span', array('class' => 'wrapper'));
                $link .= _open('span', array('class' => 'subWrapper'));
                if ($titreBloc != $pageCabinet->getTitle()) {
                    $link .= _tag('span', array('class' => 'title itemprop name', 'itemprop' => 'name'), $pageCabinet->getTitle());
                };
                $link .= _tag('meta', array('content' => $pageCabinet->createdAt, 'itemprop' => 'datePublished'));
                $link .= _close('span');
                $link .= _open('span', array('class' => 'teaser itemprop description', 'itemprop' => 'description'));
                $link .= stringTools::str_truncate($pageCabinet->getResume(), $length, '(...)', true);
                $link .= _close('span');
                $link .= _close('span');
                echo _link($pageCabinet)->set('.link_box')->text($link);

                echo _close('li');
                $i++;
            }
        echo _close('ul');
    }
}
else {
    if($this->context->getPage()->getAction() != 'show'){    
    echo _tag('h4.title',$titreBloc);
	// sinon on affiche la constante de la page concernée
        echo $constanteActuCabinet;
    }
}
?>