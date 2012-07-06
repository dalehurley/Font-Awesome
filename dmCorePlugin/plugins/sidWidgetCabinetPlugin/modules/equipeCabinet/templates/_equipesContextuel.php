<?php

// vars : $equipes, $titreBloc, $lien, $pageEquipe, $length, $rubrique, $nomRubrique, $linkAllEquipe, $mailTo
$i = 1;
$i_max = count($equipes);
$class = '';
if (count($equipes)) { // si nous avons des membres de l'article
    echo _tag('h4.title', $titreBloc);
    echo _open('ul', array('class' => 'elements'));

    foreach ($equipes as $equipe) {
        $html = '';
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

        echo _open('li', array('class' => 'element itemscope Person' . $class, 'itemtype' => 'http://schema.org/Person', 'itemscope' => 'itemscope'));

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

        $html.= _open('span', array('class' => 'wrapper'));
        $html.= _tag('span', array('class' => 'itemprop name', 'itemprop' => 'name'), __($equipe->getTitle()) . ' ' . $equipe->getFirstName() . ' ' . $equipe->getName());
        $html.= _tag('span', array('class' => 'itemprop jobTitle', 'itemprop' => 'jobTitle'), $equipe->getStatut());
        $html.= _open('span', array('class' => 'contactPoints itemscope ContactPoint', 'itemtype' => 'http://schema.org/ContactPoint', 'itemscope' => 'itemscope', 'itemprop' => 'contactPoints'));

        if ($equipe->email != NULL && $mailTo == true) {
            $html.= _open('span', array('class' => 'itemprop email'));
            $html.= _tag('span', array('class' => 'type', 'title' => __('Email')), __('Email'));
            $html.= _tag('span', array('class' => 'separator'), '&nbsp;:&nbsp;');
            $html.= _open('span', array('class' => 'value'));
            //$html.= _link('mailto:' . $equipe->email)->set(' itemprop="email"')->text('mail');
            $html.= _link('/contact?dest=' . $equipe->id)->set(' itemprop="email"')->text('E-mail');   //lien pour sidContactPlugin
            $html.= _close('span');
            $html.= _close('span');
        };
        if ($equipe->tel != NULL) {
            $html.= _open('span', array('class' => 'itemprop telephone'));
            $html.= _tag('span', array('class' => 'type', 'title' => __('Phone')), __('Phone'));
            $html.= _tag('span', array('class' => 'separator'), '&nbsp;:&nbsp;');
            $html.= _tag('span', array('class' => 'value', 'itemprop' => 'telephone'), $equipe->tel);
            $html.= _close('span');
        };
        if ($equipe->mobile != NULL) {
            $html.= _open('span', array('class' => 'itemprop cellphone'));
            $html.= _tag('span', array('class' => 'type', 'title' => __('Cellphone')), __('Cellphone'));
            $html.= _tag('span', array('class' => 'separator'), '&nbsp;:&nbsp;');
            $html.= _tag('span', array('class' => 'value', 'itemprop' => 'telephone'), $equipe->mobile);
            $html.= _close('span');
        };
        $html.= _close('span');
        $html.= _close('span');
        if ($equipe->email == NULL || $mailTo == false) {
            $linkEquipe = dmDb::table('dmPage')->findOneByModuleAndActionAndRecordId('renseignements','show',$equipe->coord_name_id);
            echo _link($linkEquipe)->anchor(dmString::slugify($equipe->getFirstName() . '-' . $equipe->getName()))->set('.link_box')->text($html);
        }
        else
            echo $html;
        echo _close('li');
        $i++;
    };
    echo _close('ul');
    if ((isset($lien)) AND ($lien != '')) {
        echo _open('div', array('class' => 'navigationWrapper navigationBottom'));
        echo _open('ul', array('class' => 'elements'));
        echo _tag('li', array('class' => 'element first last'), _link($linkAllEquipe)->text($lien)
        );
        echo _close('ul');
        echo _close('div');
    }
} // sinon on affiche rien
