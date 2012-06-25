<?php // Vars: $sidAddedPagesLevel1Pager, $theme
$i = 1;
$i_max = count($sidAddedPagesLevel1Pager);
$class = '';

if (count($sidAddedPagesLevel1Pager)) { // si nous avons un listing de pages à afficher
    //gestion affichage du titre
    if($this->context->getPage()->getModule().'/'.$this->context->getPage()->getAction() == 'sidAddedPagesGroups/show'){
        echo _tag('h4.title', $this->context->getPage()->getTitle());
    }
    else {
    echo _tag('h4.title', __('Read also '));
    }
    if (count($sidAddedPagesLevel1Pager) == 1 && ($redirect == true)) {
        header("Location: " . $header);
        exit;
    } else {
        echo $sidAddedPagesLevel1Pager->renderNavigationTop();
        echo _open('ul', array('class' => 'elements'));

        foreach ($sidAddedPagesLevel1Pager as $addedPageList) {
            $link = '';
            // class first ou last pour listing
            if ($i == 1) {
                $class = 'first';
                if ($i == $i_max)
                    $class = 'first last';
            }
            elseif ($i == $i_max)
                $class = 'last';
            else
                $class = '';

            echo _open('li', array('class' => 'element itemscope Article ' . $class, 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope'));
                if (($addedPageList->getImage()->checkFileExists() == true) and ($i <= sfConfig::get('app_nb-image'))) {
                    $link .= _open('span', array('class' => 'imageWrapper'));
                        $link .= _media($addedPageList->getImage())->size($theme['thumbs-width-image'],$theme['thumbs-height-image'])->method('scale')->set('.image itemprop="image"')->alt($addedPageList->getTitle());
                    $link .= _close('span');
                }
            $link .= _open('span', array('class' => 'wrapper'));
                $link .=_open('span', array('class' => 'subWrapper'));
                    $link .= _tag('span', array('class' => 'title itemprop name', 'itemprop' => 'name'), $addedPageList->getTitle());
                    $link .= _tag('meta', array('content' => $addedPageList->createdAt, 'itemprop' => 'datePublished'));
                $link .= _close('span');
                $link .= _open('span', array('class' => 'teaser itemprop description', 'itemprop' => 'description'));
                    $link .= stringTools::str_truncate($addedPageList->getResume(), sfConfig::get('app_length-resume'), '(...)', true);
                $link .= _close('span');
            $link .= _close('span');

            echo _link($addedPageList)->set('.link_box')->text($link);
            $i++;
            echo _close('li');
        }
        echo _close('ul');
        echo $sidAddedPagesLevel1Pager->renderNavigationBottom();
    }
}
//else {
//    if($this->context->getPage()->getAction() != 'show'){    
//    echo _tag('h4.title',$titreBloc);
//	// sinon on affiche la constante de la page concernée
//        echo $constanteActuCabinet;
//    }
//}
