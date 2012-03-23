<?php
// $vars : $adresses, $titreBloc, $visible_resume_town
//echo dm_get_widget('dmWidgetGoogleMap','show', array('$adress' => 2));
if(count($adresses)){
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

                if (($withImage == true) && ($adresse->getImage()->checkFileExists() == true)) {
                    $link .= _open('span', array('class' => 'imageWrapper'));
                        $link .= _media($adresse->getImage())->width($width)->set('.image itemprop="image"')->alt($adresse->getTitle());
                    $link .= _close('span');
                };
                $link .= _open('span', array('class' => 'wrapper'));
                $link .= _open('span', array('class' => 'subWrapper'));
                if ($titreBloc != $adresse->getTitle()) {
                    $link .= _tag('span', array('class' => 'title itemprop name', 'itemprop' => 'name'), $adresse->getTitle());
                };
                $link .= _tag('meta', array('content' => $adresse->createdAt, 'itemprop' => 'datePublished'));
                $link .= _close('span');
                $link .= _open('span', array('class' => 'teaser itemprop description', 'itemprop' => 'description'));
                $link .= stringTools::str_truncate($adresse->getResumeTown(), $length, '(...)', true);
                $link .= _close('span');
                $link .= _close('span');
                echo _link($adresse)->set('.link_box')->text($link);

                echo _close('li');
                $i++;
            }
        echo _close('ul');
}