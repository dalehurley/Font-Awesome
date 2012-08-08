<?php
// vars : $missions, $titreBloc, $lenght, $nbImagesMissions
/*$html = '';

if (count($missions)) { // si nous avons des actu articles
	
	//indique si le titre du bloc est le titre du contenu
	$isTitleContent = false;
	
	//gestion affichage du titre
    if($nbMissions == 1){
        if ($titreBloc != true) {
			$html.= get_partial('global/titleWidget', array('title' => current($missions)));
			$isTitleContent = true;
		}
        else $html.= get_partial('global/titleWidget', array('title' => $titreBloc));
    }
    else {
        if ($titreBloc != true) $html.= get_partial('global/titleWidget', array('title' => __('The other missions of the office')));
        else $html.= get_partial('global/titleWidget', array('title' => $titreBloc));
    }
    
    //ouverture du listing
    $html.= _open('ul.elements');
	
	//compteur
	$count = 0;
	$maxCount = count($missions);
	
    foreach ($missions as $mission) {
		//incrémentation compteur
		$count++;
		
		//options de la mission
		$missionOpt = array(
							'description' => $mission->getResume(),
							'dateCreated' => $mission->created_at,
							'isDateMeta' => true,
							'count' => $count,
							'maxCount' => $maxCount,
							'container' => 'li.element',
							'isListing' => true,
							'descriptionLength' => $longueurTexte,
							'url' => $mission
							);
		
		//on active le titre du contenu que lorsqu'il n'est pas affiché dans le titre du widget
		if(!$isTitleContent) $missionOpt['name'] = $mission->getTitle();
		
		$html.= get_partial('global/schema/Thing/CreativeWork/Article', $missionOpt);
    }
	
    //fermeture du listing
    $html.= _close('ul.elements');
} // sinon on affiche rien

//affichage html de sortie
echo $html;*/
if(dmConfig::get('site_theme_version') == 'v1'){
    if (count($missions)) {
        //Récupération des variables
        $ellipsis = _tag('span.ellipsis', sfConfig::get('app_vars-partial_ellipsis'));
        $i = 1;
        $i_max = count($missions);

        echo _tag('h4', array('class' => 'title'), $titreBloc);
        echo _open('ul', array('class' => 'elements'));
        foreach ($missions as $mission) {  
            $link = '';
            if($nbImagesMissions == 0) $i=0;
            //définition des options du li
            $ctnOpts = array('class' => array('element', 'itemscope', 'Article'), 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope');
            if($i == 1)         $ctnOpts['class'][] = 'first';
            if($i >= $i_max)    $ctnOpts['class'][] = 'last';

            echo _open('li', $ctnOpts);
            
            if ($withImage == true) {
                if (($mission->getImage()->checkFileExists() == true) && ($i <= $nbImagesMissions)) {
                    $link .= _open('span', array('class' => 'imageWrapper'));
                        $link .= _media($mission->getImage())->width($width)->set('.image itemprop="image"')->alt($mission->getTitle());
                    $link .= _close('span');
                }
            };
            $link .= _open('span' , array('class' => 'wrapper'));
                $link .= _open('span', array('class' => 'subWrapper'));
                    if ($titreBloc != $mission->getTitle()) {
                        $link .= _tag('span', array('class' => array('title', 'itemprop', 'name'), 'itemprop' => 'name') , $mission->getTitle());
                    };
                    $link .= _tag('meta' , array('content' => $mission->createdAt, 'itemprop' => 'datePublished'));
                $link .= _close('span');
                $link .= _open('span', array('class' => array('teaser', 'itemprop', 'description'), 'itemprop' => 'description'));

                switch ($chapo) {
                    case 0:
                        $link .= stringTools::str_truncate($mission->getResume(), $length, $ellipsis, true);
                        break;
                    case 1:
                        $link .= $mission->getText();
                        break;      
                    case 2:
                        $link .= '';
                        break;                                  
                    default:
                        $link .= '';
                        break;
                }


                $link .= _close('span');
            $link .= _close('span');
          
            echo _link($mission)->set('.link_box')->text($link);

            echo _close('li');
            $i++;
        } 
        echo _close('ul');
    }
}
elseif (dmConfig::get('site_theme_version') == 'v2') {
    if (count($missions)) {
        //Récupération des variables
        $ellipsis = _tag('span.ellipsis', sfConfig::get('app_vars-partial_ellipsis'));
        $i = 1;
        $i_max = count($missions);

        echo _tag('h4',$titreBloc);
        echo _open('ul', array('class' => 'thumbnails'));
        foreach ($missions as $mission) {  
            $link = '';
            if($nbImagesMissions == 0) $i=0;
            //définition des options du li
            $ctnOpts = array('class' => array('itemscope', 'Article'), 'itemtype' => 'http://schema.org/Article', 'itemscope' => 'itemscope');
            if($i == 1)         $ctnOpts['class'][] = 'first';
            if($i >= $i_max)    $ctnOpts['class'][] = 'last';

            echo _open('li', $ctnOpts);
                //echo _open('div', array('class' => 'row'));
                    if ($withImage == true) {
                        if (($mission->getImage()->checkFileExists() == true) && ($i <= $nbImagesMissions)) {
                                $link .= _media($mission->getImage())->width($width)->set('itemprop="image"')->alt($mission->getTitle());
                        }
                    };
                    $link .= _open('div' , array('class' => 'caption'));
                            if ($titreBloc != $mission->getTitle()) {
                                $link .= _tag('h5', array('class' => array('itemprop', 'name'), 'itemprop' => 'name') , $mission->getTitle());
                            };
                            $link .= _tag('meta' , array('content' => $mission->createdAt, 'itemprop' => 'datePublished'));
                        $link .= _open('p', array('class' => array('itemprop', 'description'), 'itemprop' => 'description'));

                        switch ($chapo) {
                            case 0:
                                $link .= stringTools::str_truncate($mission->getResume(), $length, $ellipsis, true);
                                break;
                            case 1:
                                $link .= $mission->getText();
                                break;      
                            case 2:
                                $link .= '';
                                break;                                  
                            default:
                                $link .= '';
                                break;
                        }


                        $link .= _close('p');
                    $link .= _close('div');
                  
                    echo _link($mission)->set('.thumbnail')->text($link);
                //echo _close('div');
            echo _close('li');
            $i++;
        } 
        echo _close('ul');
    }
}
?>