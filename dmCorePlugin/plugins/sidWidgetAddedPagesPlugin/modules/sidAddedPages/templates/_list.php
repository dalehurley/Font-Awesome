<?php // Vars: $sidAddedPagesPager, $theme

$i = 1;
$i_max = count($sidAddedPagesPager);
$class = '';

if (count($sidAddedPagesPager)) { // si nous avons un listing de pages à afficher
    //gestion affichage du titre
    
    // On affiche "En savoir plus"
    echo _tag('h4.title', __('Read also '));

        echo $sidAddedPagesPager->renderNavigationTop();
        echo _open('ul', array('class' => 'elements'));

        foreach ($sidAddedPagesPager as $addedPageList) {
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
        echo $sidAddedPagesPager->renderNavigationBottom();
    
}