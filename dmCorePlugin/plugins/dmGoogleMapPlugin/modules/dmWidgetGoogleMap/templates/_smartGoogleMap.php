<?php

//var = $adresses, $titreBloc
$i = 1;
$i_max = count($adresses);
$class = '';

if (count($adresses)) {
    echo _tag('h2', array('class' => 'title'), $titreBloc);
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
        
        echo _open('li', array('class' => 'element '.$class));
            $adresseCabinet = $adresse->getAdresse();
            if($adresse->getAdresse2() != NULL) {
                $adresseCabinet .='-'.$adresse->getAdresse2();
            };
            $adresseCabinet .= '-'.$adresse->getCodePostal().' '.$adresse->getVille();
            
            echo dm_get_widget('dmWidgetGoogleMap', 'show', json_decode('{"address":"'.$adresseCabinet.'","mapTypeId":"roadmap","zoom":"14","width":"350px","height":"250px","splash":"","titreBloc":"'.$adresse->getTitle().'","length":0,"widthImage":"","heightImage":"","withImage":false,"nbArticles":null,"lien":"","chapo":null,"navigationControl":false,"mapTypeControl":false,"scaleControl":false,"withResume":false,"idCabinet":'.$adresse->id.'}',true));
            
            $i++;
        echo _close('li');
            
    }
    echo _close('ul');
}
