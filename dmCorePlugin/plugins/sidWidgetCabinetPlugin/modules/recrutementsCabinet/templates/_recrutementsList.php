<?php

// vars $recrutements, $nbMissions, $titreBloc, $longueurTexte, $header, $constanteRecrutement
/* $html = get_partial('global/titleWidget', array('title' => $titreBloc));

  if (count($recrutements)) { // si nous avons des actu articles

  //ouverture du listing
  $html.= _open('ul.elements');

  //compteur
  $count = 0;
  $maxCount = count($recrutements);

  foreach ($recrutements as $recrutement) {
  //incrémentation compteur
  $count++;

  $html.= get_partial('global/schema/Thing/CreativeWork/Article', array(
  'name' => $recrutement->getTitle(),
  'description' => $recrutement->getText(),
  'dateCreated' => $recrutement->created_at,
  'isDateMeta' => true,
  'count' => $count,
  'maxCount' => $maxCount,
  'container' => 'li.element',
  'isListing' => true,
  'descriptionLength' => $longueurTexte,
  'url' => $recrutement
  ));
  }

  //fermeture du listing
  $html.= _close('ul.elements');
  }else{
  $html.= get_partial('global/schema/Thing/CreativeWork/Article', array('container' => 'article', 'articleBody' => '{{recrutement}}'));
  }

  //affichage html en sortie
  echo $html; */

if (count($recrutements)) {
    //Récupération des variables
    $ellipsis = _tag('span.ellipsis', sfConfig::get('app_vars-partial_ellipsis'));
    $i = 1;
    $i_max = count($recrutements);

    if (count($recrutements) == 1 && ($redirect == true)) {
        header("Location: " . $header);
        exit;
    }
    else {
        echo _tag('h4', array('class' => 'title'), $titreBloc);
        echo _open('ul', array('class' => 'elements'));
        foreach ($recrutements as $recrutement) {
            $link = '';

            //définition des options du li
            $ctnOpts = array('class' => array('element', 'itemscope', 'Article'), 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope');
            if($i == 1)         $ctnOpts['class'][] = 'first';
            if($i >= $i_max)    $ctnOpts['class'][] = 'last';

            echo _open('li', $ctnOpts);

            if ($withImage == true) {
                if (($recrutement->getImage()->checkFileExists() == true) && ($i <= sfConfig::get('app_nb-image'))) {
                    $link .= _open('span', array('class' => 'imageWrapper'));
                        $link .= _media($recrutement->getImage())->width($width)->set('.image itemprop="image"')->alt($recrutement->getTitle());
                    $link .= _close('span');
                }
            };
            $link .= _open('span', array('class' => 'wrapper'));
                $link .= _open('span', array('class' => 'subWrapper'));
                    if ($titreBloc != $recrutement->getTitle()) {
                        $link .= _tag('span', array('class' => array('title', 'itemprop', 'name'), 'itemprop' => 'name'), $recrutement->getTitle());
                    };
                    $link .= _tag('meta', array('content' => $recrutement->createdAt, 'itemprop' => 'datePublished'));
                $link .= _close('span');
                $link .= _open('span', array('class' => array('teaser', 'itemprop', 'description'), 'itemprop' => 'description'));
                    $link .= stringTools::str_truncate($recrutement->getText(), $length, $ellipsis, true, true);
                $link .= _close('span');
            $link .= _close('span');

            echo _link($recrutement)->set('.link_box')->text($link);
            
            echo _close('li');
            $i++;
        }
        echo _close('ul');
        
        if ((isset($lien)) AND ($lien != '')) {
            echo _open('div', array('class' => 'navigationWrapper navigationBottom'));
            echo _open('ul', array('class' => 'elements'));
            echo _tag('li', array('class' => 'element first last'), _link('recrutement/list')->text($lien)
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
        echo $constanteRecrutement;
    }
}