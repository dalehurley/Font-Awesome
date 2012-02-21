
<?php
// vars : $section, $titreBloc, $titreLien, $longueurTexte, $articles, $arrayRubrique, $photo
$html = '';

if (count($articles)) { // si nous avons des actu articles
	
	echo '<h4 class="title">'.$titreBloc.'</h4>';
		
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

		echo '<span class="navigationWrapper navigationBottom">'.
	     	'<ul class="elements">'.
      			'<li class="element first last">'.
      				_link($article->getSection())->set('.link')->text($titreLien.' '.$article->getRubriquePageTitle()).  // 2170  (415)
      			'</li>'.
      		'</ul>'.
		'</span>';
    	echo '</li>';
    	$i++;


    }
	
    //fermeture du listing
    echo _close('ul.elements');
}

