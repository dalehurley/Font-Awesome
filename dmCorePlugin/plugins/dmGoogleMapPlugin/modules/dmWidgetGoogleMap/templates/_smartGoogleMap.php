<?php

//var = $adresses, $titreBloc, $smartGoogleMap, $length, withResume
$i = 1;
$a = 1; // ancre
$i_max = count($adresses);
$class = '';

if (count($adresses)) {
    if(count($adresses)>1) {
        echo _tag('h2', array('class' => 'title'), $titreBloc);
        
        echo _open('div', array('class' => 'navigationWrapper navigationTop'));
            echo _open('ul', array('class' => 'elements'));
                foreach($adresses as $ancre) {
                     //définition des options du li
                    $ctnOpts = array('class' => array('element'));
                    if($a == 1)         $ctnOpts['class'][] = 'first';
                    if($a >= $i_max)    $ctnOpts['class'][] = 'last';

                    echo _open('li', $ctnOpts);
                        echo _link($this->context->getPage())->text($ancre->getTitle())->set('.link_box')->anchor(dmString::slugify($ancre->getTitle()).'-'.$ancre->id);
                    echo _close('li');
                    $a++;
                }
            echo _close('ul');
        echo _close('div');
    }

    //Appel des plan d'accès des différentes implantations

    foreach ($adresses as $adresse) {

        //définition des options de la section
        $ctnOpts = array('class' => array('supWrapper', 'clearfix'), 'id' => dmString::slugify($adresse->getTitle()) . '-' . $adresse->id);
        if($i == 1)         $ctnOpts['class'][] = 'first';
        if($i >= $i_max)    $ctnOpts['class'][] = 'last';

        echo _open('section', $ctnOpts);
            
            $adresseCabinet = $adresse->getAdresse();
            if($adresse->getAdresse2() != NULL) {
                $adresseCabinet .='-'.$adresse->getAdresse2();
            };
            $adresseCabinet .= '-'.$adresse->getCodePostal().' '.$adresse->getVille();

            //en attendant configuration dans les sites: on définit la largeur en fonction des templates
            //switch beaucoup plus rapide qu'un if/else
            //intégrer ces valeurs respectives pour chaque template dans la configuration par défaut de smartGoogleMap
            switch (dmConfig::get('site_theme')) {
                case 'BaseTheme': $mapWidth = 622; $mapHeight = 324; break;
                case 'operaTheme': $mapWidth = 686; $mapHeight = 360; break;
                case 'maestroTheme': $mapWidth = 494; $mapHeight = 252; break;
                //largeur du plus petit par défaut
                default: $mapWidth = 494; $mapHeight = 252; break;
            }
            echo dm_get_widget('dmWidgetGoogleMap', 'show', json_decode('{"address":"'.$adresseCabinet.'","mapTypeId":"roadmap","zoom":"14","width":"' . $mapWidth . 'px","height":"' . $mapHeight . 'px","splash":"","titreBloc":"'.$adresse->getTitle().'","length":'.$length.',"widthImage":"","heightImage":"","withImage":false,"nbArticles":null,"lien":"","chapo":null,"navigationControl":true,"mapTypeControl":false,"scaleControl":false,"withResume":'.$withResume.',"smartGoogleMap":'.$smartGoogleMap.',"idCabinet":'.$adresse->id.'}',true));

            $i++;

        echo _close('section');
            
    }
    echo _close('ul');
}
