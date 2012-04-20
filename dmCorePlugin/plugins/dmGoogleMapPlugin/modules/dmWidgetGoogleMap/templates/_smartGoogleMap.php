<?php

//var = $adresses, $titreBloc, $smartGoogleMap, $length, withResume
$i = 1;
$a = 1; // ancre
$i_max = count($adresses);
$class = '';

if (count($adresses)) {
    if(count($adresses)>1){
    echo _tag('h2', array('class' => 'title'), $titreBloc);
    
        echo _open('div', array('class' => 'navigationWrapper navigationTop'));
            echo _open('ul', array('class' => 'elements'));
                foreach($adresses as $ancre){
                    if ($a == 1) {
            $class = 'first';
            if ($a == $i_max)
                $class = 'first last';
        }
        elseif ($a == $i_max)
            $class = 'last';
        else
            $class = '';    
                echo _open('li', array('class' => 'element '.$class));
                     echo _link($this->context->getPage())->text($ancre->getTitle())->set('.link_box')->anchor(dmString::slugify($ancre->getTitle()).'-'.$ancre->id);
                echo _close('li');
                $a++;
                }
            echo _close('ul');
        echo _close('div');
    }
    echo _open('ul', array('class' => 'elements multi_map'));
    foreach ($adresses as $adresse) {
        if ($i == 1) {
            $class = 'first';
            if ($i == $i_max)
                $class = 'first last';
        }
        elseif ($i == $i_max)
            $class = 'last';
        else
            $class = '';
        
        echo _open('li', array('class' => 'element '.$class, 'id' => dmString::slugify($adresse->getTitle()).'-'.$adresse->id));
            $adresseCabinet = $adresse->getAdresse();
            if($adresse->getAdresse2() != NULL) {
                $adresseCabinet .='-'.$adresse->getAdresse2();
            };
            $adresseCabinet .= '-'.$adresse->getCodePostal().' '.$adresse->getVille();
            echo dm_get_widget('dmWidgetGoogleMap', 'show', json_decode('{"address":"'.$adresseCabinet.'","mapTypeId":"roadmap","zoom":"14","width":"350px","height":"250px","splash":"","titreBloc":"'.$adresse->getTitle().'","length":'.$length.',"widthImage":"","heightImage":"","withImage":false,"nbArticles":null,"lien":"","chapo":null,"navigationControl":false,"mapTypeControl":false,"scaleControl":false,"withResume":'.$withResume.',"smartGoogleMap":'.$smartGoogleMap.',"idCabinet":'.$adresse->id.'}',true));
            
            $i++;
        echo _close('li');
            
    }
    echo _close('ul');
}
