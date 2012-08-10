<?php // Vars: $articlePager, $parent, $route, $header
if(dmConfig::get('site_theme_version') == 'v1'){
    $html = '';
    $dateHtml = '';
    $chapeau='';
    if(count($articlePager)){
        use_helper('Date');
       if(count($articlePager) == 1){
        header("Location: ".$header);
        exit;
        }
        else {
    $articleSection = $parent . ' - ' . $route;
    //titre du contenu
    if($articleSection) echo '<h4 class="title">'.$articleSection.'</h4>';

    echo _tag('div.navigation.navigationTop', $articlePager->renderNavigationTop());

    //ouverture du listing
    echo _open('ul.elements');

    $i = 0;
    $i_max = count($articlePager->getResults()); // il faut compter le nombre de resultats pour la page en cours, count($articlePager) renvoie la taille complète du pager	

    foreach ($articlePager as $article) {
        
    	$i++;
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
            if ($article->is_dossier == true && $article->is_active == true) {
    //création du parser XML
                    //ciblage XML 
                    $xml = sfConfig::get('app_rep-local') .
                            $article->getSection()->getRubrique() .
                            '/' .
                            $article->getSection() .
                            '/' .
                            $article->filename .
                            '.xml';
                    $doc_xml = new DOMDocument();
    // vérification du fichier XML
                    if (!is_file($xml)) {
                        $html.= debugTools::infoDebug(array(__('Error : missed file') => $xml), 'warning');
                    } else {

                        //ouverture du document XML
                        if ($doc_xml->load($xml)) {

                            //récupération de l'image au dossier affiché
                            $nameImage = '';
    //                        $multimediaInserts = $doc_xml->getElementsByTagName("MultimediaInserts");
    //                        echo 'count $multimediaInserts :'.$multimediaInserts->length;
    //                        if ($multimediaInserts->length > 0) {
    //                            $multimediaInserts->item(0);
                                $multimediaImages = $doc_xml->getElementsByTagName('MultimediaInsert');
                                foreach ($multimediaImages as $multimediaImage) {
                                    $nameImage = '';
                                    $nameImage = $multimediaImage->getElementsByTagName('FileName')->item(0)->nodeValue;
                                    if (strpos($nameImage, '.jpg')){
                                        break;
                                    }
                                    
                                }
    //                        }
                            // vérification du nom de l'image en vérifiant avec le nom de l'image dans le xml et/ou avec le prefixe 'images' ???
                            $imageExist = false;
                            $imageLink = '/_images/' . $nameImage;
                            if (is_file(sfConfig::get('sf_web_dir') . $imageLink)) {
                                $imageExist = true;
                            } elseif (!is_file(sfConfig::get('sf_web_dir') . $imageLink)) {
                                $imageLink = '/_images/images' . $nameImage;
                                if (is_file(sfConfig::get('sf_web_dir') . $imageLink)) {
                                    $imageExist = true;
                                }
                            }
                            else
                                $imageExist = false;
                        }
                    }
                    if (isset($imageExist) && $imageExist == true) {
                        $imageLink = $imageLink;
                    }
                }
    else{
    	
    	$imageLink = '/_images/lea' . $article->filename . '-p.jpg';
    }
    	$imageHtml = '';
            //on supprime les photos après les 3 premiers articles
    	if (isset($imageLink) && is_file(sfConfig::get('sf_web_dir').$imageLink) && $i < 4 ){  // les 3 premiers articles ont une image
    		$imageHtml = 	
    			'<span class="imageWrapper">'.
    				'<img src="'.$imageLink.'" width="260" itemprop="image" class="image" alt="'.$article->getTitle().'">'.
    			'</span>';
    	}

    	//ajout de l'article
            // suppression de la date de création si page AGENDA
            if($article->Section->getRubrique() != 'ec_echeancier'){
                $dateHtml = '<meta content="'.$article->createdAt.'" itemprop="datePublished">'.
                '<span class="date">'.__('published on').' '.
                    '<time itemprop="datePublished" class="datePublished" pubdate="pubdate" datetime="'.$article->updated_at.'">'.format_date($article->updated_at, 'D').'</time>'.
                '</span>';
            }
            
            if($article->getSection() != 'ec_chiffres'){$chapeau = '<span itemprop="description" class="teaser itemprop description">'.$article->getChapeau().'</span>';}
            else $chapeau ='';
    	echo 
    	'<li itemtype="http://schema.org/Article" itemscope="itemscope" class="element itemscope Article'.$position.'">';
    	echo _link($article)->set('.link.link_box')->text(
    			$imageHtml.
    			'<span class="wrapper">'.
    				'<span class="subWrapper">'.
    					'<span itemprop="name" class="title itemprop name">'.$article->getTitle().'</span>'.
    				$dateHtml.	
    				'</span>'.
    				$chapeau.
    			'</span>'
    	);
    	echo '</li>';
            

    }

    //fermeture du listing
    echo _close('ul.elements');

    echo _tag('div.navigation.navigationBottom', $articlePager->renderNavigationBottom());
        }
    }
}
elseif(dmConfig::get('site_theme_version') == 'v2'){
    $html = '';
    $dateHtml = '';
    $chapeau='';
    if(count($articlePager)){
        use_helper('Date');
       if(count($articlePager) == 1){
        header("Location: ".$header);
        exit;
        }
        else {
    $articleSection = $parent . ' - ' . $route;
    //titre du contenu
    if($articleSection) echo '<h4>'.$articleSection.'</h4>';

    echo _tag('div.navigation.navigationTop', $articlePager->renderNavigationTop());

    //ouverture du listing
    echo _open('ul.thumbnails');

    $i = 0;
    $i_max = count($articlePager->getResults()); // il faut compter le nombre de resultats pour la page en cours, count($articlePager) renvoie la taille complète du pager  

    foreach ($articlePager as $article) {
        
        $i++;
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
            if ($article->is_dossier == true && $article->is_active == true) {
    //création du parser XML
                    //ciblage XML 
                    $xml = sfConfig::get('app_rep-local') .
                            $article->getSection()->getRubrique() .
                            '/' .
                            $article->getSection() .
                            '/' .
                            $article->filename .
                            '.xml';
                    $doc_xml = new DOMDocument();
    // vérification du fichier XML
                    if (!is_file($xml)) {
                        $html.= debugTools::infoDebug(array(__('Error : missed file') => $xml), 'warning');
                    } else {

                        //ouverture du document XML
                        if ($doc_xml->load($xml)) {

                            //récupération de l'image au dossier affiché
                            $nameImage = '';
    //                        $multimediaInserts = $doc_xml->getElementsByTagName("MultimediaInserts");
    //                        echo 'count $multimediaInserts :'.$multimediaInserts->length;
    //                        if ($multimediaInserts->length > 0) {
    //                            $multimediaInserts->item(0);
                                $multimediaImages = $doc_xml->getElementsByTagName('MultimediaInsert');
                                foreach ($multimediaImages as $multimediaImage) {
                                    $nameImage = '';
                                    $nameImage = $multimediaImage->getElementsByTagName('FileName')->item(0)->nodeValue;
                                    if (strpos($nameImage, '.jpg')){
                                        break;
                                    }
                                    
                                }
    //                        }
                            // vérification du nom de l'image en vérifiant avec le nom de l'image dans le xml et/ou avec le prefixe 'images' ???
                            $imageExist = false;
                            $imageLink = '/_images/' . $nameImage;
                            if (is_file(sfConfig::get('sf_web_dir') . $imageLink)) {
                                $imageExist = true;
                            } elseif (!is_file(sfConfig::get('sf_web_dir') . $imageLink)) {
                                $imageLink = '/_images/images' . $nameImage;
                                if (is_file(sfConfig::get('sf_web_dir') . $imageLink)) {
                                    $imageExist = true;
                                }
                            }
                            else
                                $imageExist = false;
                        }
                    }
                    if (isset($imageExist) && $imageExist == true) {
                        $imageLink = $imageLink;
                    }
                }
    else{
        
        $imageLink = '/_images/lea' . $article->filename . '-p.jpg';
    }
        $imageHtml = '';
            //on supprime les photos après les 3 premiers articles
        if (isset($imageLink) && is_file(sfConfig::get('sf_web_dir').$imageLink) && $i < 4 ){  // les 3 premiers articles ont une image
            $imageHtml = '<div class="span">'.
                            '<img src="'.$imageLink.'" width="260" alt="'.$article->getTitle().'">'.
                        '</div>';
        }

        //ajout de l'article
        // suppression de la date de création si page AGENDA
        if($article->Section->getRubrique() != 'ec_echeancier'){
            $dateHtml = '<p>'.
                            '<em>'.
                                __('published on').' '.format_date($article->updated_at, 'D').
                            '</em>'.
                        '</p>';
        }
        
        if($article->getSection() != 'ec_chiffres'){
            $chapeau = '<p>'.$article->getChapeau().'</p>';
        }
        else $chapeau ='';
        echo  '<li class="'.$position.'">';
            echo _link($article)->set('.thumbnail')->text(
                '<div class="row">'.
                    $imageHtml.
                    '<div class="span">'.
                        '<h5>'.$article->getTitle().'</h5>'.
                        $dateHtml. 
                        $chapeau.
                    '</div>'.
                '</div>'
            );
        echo '</li>';
            

    }

    //fermeture du listing
    echo _close('ul');

    echo _tag('div.navigation.navigationBottom', $articlePager->renderNavigationBottom());
        }
    }
}