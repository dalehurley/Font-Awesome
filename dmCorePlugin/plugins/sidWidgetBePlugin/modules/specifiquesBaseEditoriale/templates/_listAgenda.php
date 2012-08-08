<?php
// vars : $section, $titreBloc, $lien, $length, $articles, $arrayRubrique, $photo
if (dmConfig::get('site_theme_version') == 'v1'){
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
}
elseif (dmConfig::get('site_theme_version') == 'v2'){
	if (count($articles)) { // si nous avons des actu articles
		if($titreBloc) echo _tag('h3',$titreBloc);
			
		//ouverture du listing
	    echo _open('ul.thumbnails');

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

	        $textHtml = "";
            $textHtml .= _open('div', array('class' => 'caption'));
                // affichage du titre
                $textHtml .= _tag('h5', $article->getTitle());

                // affichage du chapeau
                $textHtml .= _tag('p',
                    array('class' => 'itemprop description', 'itemprop' => 'description'),
                    stringTools::str_truncate($article->getChapeau(), $length, '(...)', true)
                    );

            $textHtml .= _close('div');

		    // affichage de l'article
		    echo _open('li');
    			echo _link($article)->set('.thumbnail')->text($textHtml);
    		echo _close('li');
	        
	            $i++;
	            
	        }
		
	    //fermeture du listing
	    echo _close('ul');
	    
        if ($lien != '') {
        	echo _open('div', array('class' => 'btn-toolbar'));
	        	echo _open('div', array('class' => 'btn-group'));
	        		echo _link($article->getSection())->text($lien)->set('.btn');
	        	echo _close('div');
        	echo _close('div');
        };
        echo _open('div', array('class' => 'btn-toolbar'));
	        echo _open('div', array('class' => 'btn-group'));
            	echo _link($vacances)->text($vacances->getName())->set('.btn');
            echo _close('div');
        echo _close('div');
	    
	} else {
		echo debugTools::infoDebug(array(__('Agenda') => __('No entries for current month')),'warning');
	}
}


