<?php

// vars $selections, $nbMissions, $titreBloc, $longueurTexte, $header, $constanteSelection

if (count($selections)) {
    $i = 1;
    $i_max = count($selections);
    $class = '';
    if (count($selections) == 1 && ($redirect == true)) {
        header("Location: " . $header);
        exit;
    } 
    else {
        echo _tag('h4', array('class' => 'title'), $titreBloc);
        echo _open('ul', array('class' => 'elements'));
        foreach ($selections as $selection) {
            $link = '';
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
            if ($withImage == true) {
                if (($selection->getImage()->checkFileExists() == true) and ($i <= sfConfig::get('app_nb-image'))) {
                    $link .= _open('span', array('class' => 'imageWrapper'));
                    $link .= _media($selection->getImage())->width($width)->set('.image itemprop="image"');
                    $link .= _close('span');
                }
            };
            $link .= _open('span', array('class' => 'wrapper'));
            $link .= _open('span', array('class' => 'subWrapper'));

            if ($titreBloc != $selection->getTitle()) {
                $link .= _tag('span', array('class' => 'title itemprop name', 'itemprop' => 'name'), $selection->getTitle());
            };
            $link .= _tag('meta', array('content' => $selection->createdAt, 'itemprop' => 'datePublished'));
            $link .= _close('span');
            $link .= _open('span', array('class' => 'teaser itemprop description', 'itemprop' => 'description'));
            $link .= stringTools::str_truncate($selection->getText(), $length, '(...)', true, true);
            $link .= _close('span');
            $link .= _close('span');

            echo _link($selection)->set('.link_box')->text($link);
            $i++;
            echo _close('li');
        }
        echo _close('ul');
        
        if ((isset($lien)) AND ($lien != '')) {
            echo _open('div', array('class' => 'navigationWrapper navigationBottom'));
            echo _open('ul', array('class' => 'elements'));
            echo _tag('li', array('class' => 'element first last'), _link($namePage->module.'/'.$namePage->action)->text($lien)
            );
            echo _close('ul');
            echo _close('div');
        } 
    }
}
else {
    if($this->context->getPage()->getAction() != 'show'){    
    echo _tag('h4.title',$titreBloc);
	// sinon on affiche la constante de la page concernée
        echo $constanteSelection;
    }
}