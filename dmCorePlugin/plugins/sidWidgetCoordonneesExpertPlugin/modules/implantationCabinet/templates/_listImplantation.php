<?php
// $vars : $adresses, $titreBloc, $visible_resume_town
//echo dm_get_widget('dmWidgetGoogleMap','show', array('$adress' => 2));
if(count($adresses)){
echo _tag('h2.title', $titreBloc);
$i = 1;
$i_max = count($adresses);
$class = '';
$html = '';
echo _open('ul.elements');

    foreach($adresses as $adresse){
    // condition pour gérer les class des listings
    if ($i == 1) {
        $class = ' first';
        if ($i == $i_max)
                $class = ' first last';
    }
    elseif ($i == $i_max)
        $class = ' last';
    else
        $class = '';
    // condition pour gérer les class des listings
    if($visible_resume_town == TRUE && $adresse->getResumeTown() != NULL){
        $resume_town = _tag('span', array('class' => 'teaser itemprop description', 'itemprop' => 'description'), $adresse->getResumeTown());
    }
    else $resume_town = '';
    
    $html = _tag('span.wrapper', 
                _tag('span', array('class' => 'subWrapper'), 
                    _tag('span', array('class' => 'title itemprop name', 'itemprop' => 'name'),$adresse->getTitle())
                        ).$resume_town
                
                );
    
    echo _open('li', array('class' => 'element '.$class));
        echo _link($adresse)->text($html)->set('.link_box');
    echo _close('li');
    $i++;
    $html='';
    }

echo _close('ul');   
}