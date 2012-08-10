<?php

// vars $selections, $nbMissions, $titreBloc, $longueurTexte, $header, $constanteSelection
if (dmConfig::get('site_theme_version') == 'v1'){
    if (count($selections)) {
        //Récupération des variables
        $ellipsis = _tag('span.ellipsis', sfConfig::get('app_vars-partial_ellipsis'));
        $i = 1;
        $i_max = count($selections);

        if (count($selections) == 1 && ($redirect == true)) {
            header("Location: " . $header);
            exit;
        } 
        else {
            echo _tag('h4', array('class' => 'title'), $titreBloc);
            echo _open('ul', array('class' => 'elements'));
            foreach ($selections as $selection) {
                $link = '';

                //définition des options du li
                $ctnOpts = array('class' => array('element', 'itemscope', 'Article'), 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope');
                if($i == 1)         $ctnOpts['class'][] = 'first';
                if($i >= $i_max)    $ctnOpts['class'][] = 'last';

                echo _open('li', $ctnOpts);

                if ($withImage == true) {
                    if (($selection->getImage()->checkFileExists() == true) && ($i <= sfConfig::get('app_nb-image'))) {
                        $link .= _open('span', array('class' => 'imageWrapper'));
                        $link .= _media($selection->getImage())->width($width)->set('.image itemprop="image"');
                        $link .= _close('span');
                    }
                };
                $link .= _open('span', array('class' => 'wrapper'));
                    $link .= _open('span', array('class' => 'subWrapper'));
                        if ($titreBloc != $selection->getTitle()) {
                            $link .= _tag('span', array('class' => array('title', 'itemprop', 'name'), 'itemprop' => 'name'), $selection->getTitle());
                        };
                        $link .= _tag('meta', array('content' => $selection->createdAt, 'itemprop' => 'datePublished'));
                    $link .= _close('span');
                    $link .= _open('span', array('class' => array('teaser', 'itemprop', 'description'), 'itemprop' => 'description'));
                        $link .= stringTools::str_truncate($selection->getText(), $length, $ellipsis, true, true);
                    $link .= _close('span');
                $link .= _close('span');

                echo _link($selection)->set('.link_box')->text($link);
                
                echo _close('li');
                $i++;
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
}
elseif (dmConfig::get('site_theme_version') == 'v2'){
  if (count($selections)) {
      //Récupération des variables
      $ellipsis = _tag('span.ellipsis', sfConfig::get('app_vars-partial_ellipsis'));
      $i = 1;
      $i_max = count($selections);

      if (count($selections) == 1 && ($redirect == true)) {
          header("Location: " . $header);
          exit;
      }
      else {
          echo _tag('h3', $titreBloc);
          echo _open('ul', array('class' => 'thumbnails'));
          foreach ($selections as $selection) {
              $link = '';

              //définition des options du li
              $ctnOpts = array('class' => array('row itemscope', 'Article'), 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope');
              if($i == 1)         $ctnOpts['class'][] = 'first';
              if($i >= $i_max)    $ctnOpts['class'][] = 'last';

              echo _open('li', $ctnOpts);

              if ($withImage == true) {
                  if (($selection->getImage()->checkFileExists() == true) && ($i <= sfConfig::get('app_nb-image'))) {
                          $link .= _media($selection->getImage())->width($width)->set(' itemprop="image"')->alt($selection->getTitle());
                  }
              };
              $link .= _open('div', array('class' => 'caption'));
                  if ($titreBloc != $selection->getTitle()) {
                      $link .= _tag('h5', array('class' => array('itemprop', 'name'), 'itemprop' => 'name'), $selection->getTitle());
                  };
                  $link .= _tag('meta', array('content' => $selection->createdAt, 'itemprop' => 'datePublished'));
                  $link .= _tag('p', array('class' => array('itemprop', 'description'), 'itemprop' => 'description'),stringTools::str_truncate($selection->getText(), $length, $ellipsis, true, true));
              $link .= _close('div');

              echo _link($selection)->set('.thumbnail')->text($link);
              
              echo _close('li');
              $i++;
          }
          echo _close('ul');
          
          //if ((isset($lien)) AND ($lien != '')) {
          //    echo _open('div', array('class' => 'navigationWrapper navigationBottom'));
          //    echo _open('ul', array('class' => 'elements'));
          //    echo _tag('li', array('class' => 'element first last'), _link('selection/list')->text($lien)
          //    );
          //    echo _close('ul');
          //    echo _close('div');
          //} 
      }
  }
}