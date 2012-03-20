<?php
/*
// var $agendas - var $rubriqueTitle - var $rubrique
$html = '';


if(count($agendas)){
	
	$html.= get_partial('global/titleWidget', array('title' => $rubriqueTitle));
	
	//ouverture du listing
    $html.= _open('ul.elements');
	
	//compteur
	$count = 0;
	$maxCount = count($agendas);
	
    foreach ($agendas as $agenda) {
		//incrémentation compteur
		$count++;
		
		$html.= get_partial('global/schema/Thing/CreativeWork/Article', array(
												'name' => $agenda->getTitle(),
												'description' => $agenda->getChapeau(),
												'count' => $count,
												'maxCount' => $maxCount,
												'container' => 'li.element',
												'isListing' => true,
												'descriptionLength' => $length,
												'url' => $agenda
												));
    }
	
    //fermeture du listing
    $html.= _close('ul.elements');
	
	//création d'un tableau de liens à afficher
	$elements = array();
	$elements[] = array('title' => $lien, 'linkUrl' => $agendas[0]->getSection());
	
	$html.= get_partial('global/navigationWrapper', array(
													'placement' => 'bottom',
													'elements' => $elements
													));
} else {
	$html .= debugTools::infoDebug(array(__('Agenda') => __('No entries for current month')),'warning');
}

//affichage html en sortie
echo $html;
*/


// vars : $section, $titreBloc, $lien, $longueurTexte, $articles, $arrayRubrique, $photo

if (count($articles)) { // si nous avons des actu articles
    
	
	if($titreBloc) echo '<h4 class="title">'.$titreBloc.'</h4>';
		
	//ouverture du listing
    echo _open('ul.elements');

	//compteur
	$i = 1;
	$i_max = count($articles);

    foreach ($articles as $article) {

    	$position = '';
        switch ($i){
            case '1' : 
            	if ($i_max == 1) $position = ' first last';
            	else $position = ' first';
                break;
            default : 
            	if ($i == $i_max) $position = ' last';
            	else $position = '';
            	break;
        }

		// gestion de l'image    	
    	$imageFile = '/_images/lea' . $article->filename . '-p.jpg';
    	$imageHtml = '';
    	if (is_file(sfConfig::get('sf_web_dir').$imageFile) && $withImage){
      	   	$imageHtml = '<span class="imageWrapper">'.
      	   		_media($imageFile)->width($widthImage)->set('.image itemprop="image"')->alt($article). 
	      	'</span>';
	    }
	    // affichage de l'article
    	echo '<li class="element itemscope Article'.$position.'" itemscope="itemscope" itemtype="http://schema.org/Article">';
    	echo _link($article)->set('.link.link_box')->text(         
    		$imageHtml.
	      	'<span class="wrapper">'.
	      		'<span class="subWrapper">'.
	      			'<span class="title itemprop name" itemprop="name">'.$article.'</span>'.
	      		'</span>'.
	      		'<span class="teaser itemprop description" itemprop="description">'.
		      		stringTools::str_truncate($article->getChapeau(), $length, '(...)', true).
		      	'</span>'.
		    '</span>'
		);

          if ($lien != '') {
            if ($i == $i_max)
                echo
                '<span class="navigationWrapper navigationBottom">' .
                    '<ul class="elements">' .
                        '<li class="element first">' .
                            _link($article->getSection())->text($lien) .
                        '</li>' .
                        '<li class="element last">' .
                            _link($vacances)->text($vacances->getName()) .
                        '</li>' .
                    '</ul>' .
                '</span>';
          }
            echo '</li>';
            $i++;
            
        }
	
    //fermeture du listing
    echo _close('ul.elements');
    
} else {
	echo debugTools::infoDebug(array(__('Agenda') => __('No entries for current month')),'warning');
}


