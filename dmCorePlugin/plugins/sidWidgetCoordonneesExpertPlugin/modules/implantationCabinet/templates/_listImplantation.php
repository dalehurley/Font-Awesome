<?php
// $vars : $adresses, $titreBloc, $visible_resume_town
if (dmConfig::get('site_theme_version') == 'v1'){
    //echo dm_get_widget('dmWidgetGoogleMap','show', array('$adress' => 2));
    if (count($adresses)) {
        if (count($adresses) == 1 && ($redirect == true)) {
            header("Location: " . $header);
            exit;
        } else {
            echo _tag('h2.title', $titreBloc);
            $i = 1;
            $i_max = count($adresses);
            $class = '';
            $html = '';
            echo _open('ul', array('class' => 'elements'));
            foreach ($adresses as $adresse) {
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
                if($visible_resume_town == true){
                if (($withImage == true) && ($adresse->getImage()->checkFileExists() == true)) {
                    $link .= _open('span', array('class' => 'imageWrapper'));
                    $link .= _media($adresse->getImage())->width($width)->set('.image itemprop="image"')->alt($adresse->getTitle());
                    $link .= _close('span');
                };
                };
                $link .= _open('span', array('class' => 'wrapper'));
                $link .= _open('span', array('class' => 'subWrapper'));
                if ($titreBloc != $adresse->getTitle()) {
                    $link .= _tag('span', array('class' => 'title itemprop name', 'itemprop' => 'name'), $adresse->getTitle());
                };
                $link .= _tag('meta', array('content' => $adresse->createdAt, 'itemprop' => 'datePublished'));
                $link .= _close('span');
                if($visible_resume_town == true){
                $link .= _open('span', array('class' => 'teaser itemprop description', 'itemprop' => 'description'));
                $link .= stringTools::str_truncate($adresse->getResumeTown(), $length, '(...)', true);
                $link .= _close('span');
                };
                $link .= _close('span');
                echo _link($adresse)->set('.link_box')->text($link);

                echo _close('li');
                $i++;
            }
            echo _close('ul');
        }
    }
}
elseif (dmConfig::get('site_theme_version') == 'v2'){
    //echo dm_get_widget('dmWidgetGoogleMap','show', array('$adress' => 2));
    if (count($adresses)) {
        if (count($adresses) == 1 && ($redirect == true)) {
            header("Location: " . $header);
            exit;
        } else {
            echo _tag('h2', $titreBloc);
            $i = 1;
            $i_max = count($adresses);
            $class = '';
            $html = '';
            echo _open('ul.thumbnails');
            foreach ($adresses as $adresse) {
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


                echo _open('li', array('class' => 'itemscope Article ' . $position, 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope'));


                $link = '';
                if($visible_resume_town == true){
                    if (($withImage == true) && ($adresse->getImage()->checkFileExists() == true)) {
                        $link .= _media($adresse->getImage())->width($width)->set('itemprop="image"')->alt($adresse->getTitle());
                    };
                };

                if ($titreBloc != $adresse->getTitle()) {
                    $link .= _tag('h4', array('class' => 'itemprop name', 'itemprop' => 'name'), $adresse->getTitle());
                };
                $link .= _open('div', array('class' => 'caption'));
                    $link .= _tag('meta', array('content' => $adresse->createdAt, 'itemprop' => 'datePublished'));
                    if($visible_resume_town == true){
                        $link .= _open('p', array('class' => 'itemprop description', 'itemprop' => 'description'));
                            $link .= stringTools::str_truncate($adresse->getResumeTown(), $length, '(...)', true);
                        $link .= _close('p');
                    };
                $link .= _close('div');
                echo _link($adresse)->set('.thumbnail')->text($link);

                echo _close('li');
                $i++;
            }
            echo _close('ul');
        }
    }
}
