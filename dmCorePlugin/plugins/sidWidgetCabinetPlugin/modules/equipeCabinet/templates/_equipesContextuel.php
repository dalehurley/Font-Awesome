<?php
// vars : $equipes, $titreBloc, $titreLien, $pageEquipe, $length, $rubrique, $nomRubrique, $linkEquipe
$i = 1;
$i_max = count($equipes);
$class ='';
if (count($equipes)) { // si nous avons des membres de l'article
echo _tag('h4.title',$titreBloc);
echo _open('ul', array('class' => 'elements'));

foreach($equipes as $equipe) {
        // condition pour gérer les class des listings
        if ($i == 1) {
            $class = 'first';
            if ($i == $i_max)
                $class = 'first last';
        }
        elseif ($i == $i_max)
            $class = 'last';
        else
            $class = '';
                // condition pour gérer les class des listings

                echo _open('li', array('class' => 'element itemscope Person ' . $class, 'itemtype' => 'http://schema.org/Person', 'itemscope' => 'itemscope'));

                    if (($withImage == TRUE) && $equipe->getImage()->checkFileExists() == true) {
                        
                        echo _tag('span', array('class' => 'imageWrapper'), _media($equipe->getImage())->width($width)->alt($equipe->getTitle())->set('.image itemprop="image"'));
                    };
                    echo _open('span', array('class' => 'wrapper'));
                        echo _tag('span', array('class' => 'itemprop name', 'itemprop' => 'name'), $equipe->getTitle());
                        echo _tag('span', array('class' => 'itemprop jobTitle', 'itemprop' => 'jobTitle'), $equipe->getStatut());
                        echo _open('span', array('class' => 'contactPoints itemscope ContactPoint', 'itemtype' => 'http://schema.org/ContactPoint', 'itemscope' => 'itemscope', 'itemprop' => 'contactPoints'));
                            
                            if ($equipe->email != NULL) {
                                echo _open('span', array('class' => 'itemprop email'));
                                echo _tag('span', array('class' => 'type', 'title' => __('Email')), __('Email'));
                                echo _tag('span', array('class' => 'separator'), '&nbsp;:&nbsp;');
                                echo _open('span', array('class' => 'value'));
                                echo _link('mailto:' . $equipe->email)->set(' itemprop="email"')->text('mail');
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
                            if ($equipe->gsm != NULL) {
                                echo _open('span', array('class' => 'itemprop cellphone'));
                                echo _tag('span', array('class' => 'type', 'title' => __('Cellphone')), __('Cellphone'));
                                echo _tag('span', array('class' => 'separator'), '&nbsp;:&nbsp;');
                                echo _tag('span', array('class' => 'value', 'itemprop' => 'cellphone'), $equipe->gsm);
                                echo _close('span');
                            };
                        echo _close('span');
                    echo _close('span');
                echo _close('li');
                $i++;
                };
            echo _close('ul');
if ((isset($lien)) AND ($lien != '')) { 
        echo _open('div', array('class' => 'navigationWrapper navigationBottom'));
            echo _open('ul', array('class' => 'elements'));
                echo _tag('li', array('class' => 'element first last'), 
                        _link($linkEquipe)->text($lien)
                        );
            echo _close('ul');
        echo _close('div');
    
    }
    
} // sinon on affiche rien
