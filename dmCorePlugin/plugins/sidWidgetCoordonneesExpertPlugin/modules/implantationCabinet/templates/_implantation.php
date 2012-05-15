<?php

// vars : $equipes, $titreBloc, $civ, $withImage, $adresse, $visible_resume_team
$html = '';
$i = 1;
$i_max = count($equipes);
$class = '';
// vars  $equipes, $titreBloc, $nomRubrique
if (count($equipes)) { // si nous avons des actu articles
// initialisation des variables pour les class first et last
// initialisation des variables pour les class first et last
    echo _tag('h2', array('class' => 'title'), $titreBloc);
    
echo _open('section', array('class' => 'supWrapper clearfix first last'));
    if($visible_resume_team == TRUE && $adresse->resume_team != ''){
        echo _tag('div.wrapper', $adresse->resume_team);
    }

    echo _open('ul.elements');
    foreach ($equipes as $equipe) {
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

        echo _open('li', array('class' => 'element itemscope Person ' . $class, 'itemtype' => 'http://schema.org/Person', 'itemscope' => 'itemscope', 'id' => dmString::slugify($equipe->getFirstName() . '-' . $equipe->getName())));

        // nouveau code pour arnaud afin de styler la silhouttee du membre de l'équipe qui n'a pas de photo
        if($withImage == TRUE){
            if($equipe->getImage()->checkFileExists() == true) {
                //on affiche directement la photo de la personne
                echo _tag('span', array('class' => 'imageWrapper'), _media($equipe->getImage())->width($width)->method('scale')->alt($equipe->getFirstName() . '-' . $equipe->getName())->set('.image itemprop="image"'));
            }
            else {
                //on détecte le sexe de la personne
                $personGenre = ($equipe->getTitle() == 'Mr') ? 'male' : 'female';

                //détection de la taille des miniatures à sélectionner
                if($width >= 110) $spriteFormat = 'spriteFormat_X';
                elseif($width >= 55) $spriteFormat = 'spriteFormat_L';
                elseif($width >= 27) $spriteFormat = 'spriteFormat_M';
                else $spriteFormat = 'spriteFormat_S';

                //on affiche un imageWrapper de la largeur des images, puis un span dans lequel sera affiché la silhouette en CSS
                echo _tag('span', array('class' => array('imageWrapper', 'noImage', 'buddy', $spriteFormat), 'style' => 'width:' . $width . 'px;'), _tag('span', array('class' => array('image', $personGenre)), '&#160;'));
            }
        };

        if($civ == TRUE) $civ = $equipe->getTitle();
        echo _open('span', array('class' => 'wrapper'));
        echo _tag('span', array('class' => 'itemprop name', 'itemprop' => 'name'), __($civ) . ' ' . $equipe->getFirstName() . ' ' . $equipe->getName());
        echo _tag('span', array('class' => 'itemprop jobTitle', 'itemprop' => 'jobTitle'), $equipe->getStatut());
        echo _open('span', array('class' => 'contactPoints itemscope ContactPoint', 'itemtype' => 'http://schema.org/ContactPoint', 'itemscope' => 'itemscope', 'itemprop' => 'contactPoints'));
        if (isset($nomRubrique[$equipe->id])) {
                                echo _open('span', array('class' => 'itemprop contactType'));
                                echo _tag('span', array('class' => 'type', 'title' => __('Responsable in')), __('Responsable in'));
                                echo _tag('span', array('class' => 'separator'), '&nbsp;:&nbsp;');
                                echo _tag('span', array('class' => 'value', 'itemprop' => 'contactType'), $nomRubrique[$equipe->id]);
                                echo _close('span');
                                };
        if ($equipe->email != NULL) {
            echo _open('span', array('class' => 'itemprop email'));
            echo _tag('span', array('class' => 'type', 'title' => __('Email')), __('Email'));
            echo _tag('span', array('class' => 'separator'), '&nbsp;:&nbsp;');
            echo _open('span', array('class' => 'value'));
            echo _link('mailto:' . $equipe->email)->set(' itemprop="email"')->text($equipe->email);
            echo _close('span');
            echo _close('span');
        };
        if ($equipe->tel != NULL) {
            echo _open('span', array('class' => 'itemprop telephone'));
            echo _tag('span', array('class' => 'type', 'title' => __('Phone')), __('Phone'));
            echo _tag('span', array('class' => 'separator'), '&nbsp;:&nbsp;');
            echo _tag('span', array('class' => 'value', 'itemprop' => 'telephone'), $equipe->tel);
            echo _close('span');
        };
        if ($equipe->mobile != NULL) {
            echo _open('span', array('class' => 'itemprop cellphone'));
            echo _tag('span', array('class' => 'type', 'title' => __('Cellphone')), __('Cellphone'));
            echo _tag('span', array('class' => 'separator'), '&nbsp;:&nbsp;');
            echo _tag('span', array('class' => 'value', 'itemprop' => 'cellphone'), $equipe->mobile);
            echo _close('span');
        };
        echo _close('span');
        echo _tag('span', array('class' => 'itemprop description', 'itemprop' => 'description'), strip_tags($equipe->getText(), '<sup><sub><b><i>'));
        echo _close('span');
        echo _close('li');
        $i++;
    };
    echo _close('ul');
    echo _close('section');
} else {
    $html.= "Aucun membre de l'équipe n'est présenté.";
}


