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
    $i = 1;
    $i_max = count($recrutements);
    $class = '';
    if (count($recrutements) == 1 && ($redirect == true)) {
        header("Location: " . $header);
        exit;
    } 
    else {
        echo _tag('h4', array('class' => 'title'), $titreBloc);
        echo _open('ul', array('class' => 'elements'));
        foreach ($recrutements as $recrutement) {
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
                if (($recrutement->getImage()->checkFileExists() == true) and ($i <= sfConfig::get('app_nb-image'))) {
                    $link .= _open('span', array('class' => 'imageWrapper'));
                    $link .= _media($recrutement->getImage())->width($width)->set('.image itemprop="image"')->alt($recrutement->getTitle());
                    $link .= _close('span');
                }
            };
            $link .= _open('span', array('class' => 'wrapper'));
            $link .= _open('span', array('class' => 'subWrapper'));

            if ($titreBloc != $recrutement->getTitle()) {
                $link .= _tag('span', array('class' => 'title itemprop name', 'itemprop' => 'name'), $recrutement->getTitle());
            };
            $link .= _tag('meta', array('content' => $recrutement->createdAt, 'itemprop' => 'datePublished'));
            $link .= _close('span');
            $link .= _open('span', array('class' => 'teaser itemprop description', 'itemprop' => 'description'));
            $link .= stringTools::str_truncate($recrutement->getText(), $length, '(...)', true, true);
            $link .= _close('span');
            $link .= _close('span');

            echo _link($recrutement)->set('.link_box')->text($link);
            $i++;
            echo _close('li');
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
    echo _tag('h4', array('class' => 'title'), $titreBloc);
    echo $constanteRecrutement;
}