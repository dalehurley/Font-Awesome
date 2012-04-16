
<?php
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
        if ($article->is_dossier == true) {
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
                        
//                        $multimediaInserts = $doc_xml->getElementsByTagName("MultimediaInserts");
//                        echo 'count $multimediaInserts :'.$multimediaInserts->length;
//                        if ($multimediaInserts->length > 0) {
//                            $multimediaInserts->item(0);
                            $multimediaImages = $doc_xml->getElementsByTagName('MultimediaInsert');
                            foreach ($multimediaImages as $multimediaImage) {
                                $nameImage = '';
                                $nameImage = $multimediaImage->getElementsByTagName('FileName')->item(0)->nodeValue;
                                if (strpos($nameImage, '-p.jpg')){
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
                if ($imageExist) {
                    $imageLink = $imageLink;
                }
            }
else{
    
    $imageLink = '/_images/lea' . $article->filename . '-p.jpg';
    }

		// gestion de l'image    	
    	
    	$imageHtml = '';
    	if (is_file(sfConfig::get('sf_web_dir').$imageLink) && $withImage){
      	   	$imageHtml = '<span class="imageWrapper">'.
      	   		_media($imageLink)->width($widthImage)->set('.image itemprop="image"')->alt($article). 
	      	'</span>';
	    }

        $textHtml = '';
        $chapeau = '';
        if($justTitle == false){
            $chapeau = '<span class="teaser itemprop description" itemprop="description">'.
                        stringTools::str_truncate($article->getChapeau(), $length, '(...)', true).
                    '</span>';
        }
        $textHtml = 
            '<span class="wrapper">'.
                '<span class="subWrapper">'.
                    '<span class="title itemprop name" itemprop="name">'.$article.'</span>'.
                '</span>'.
                $chapeau.
            '</span>';

	    // affichage de l'article
    	echo '<li class="element itemscope Article'.$position.'" itemscope="itemscope" itemtype="http://schema.org/Article">';
    	echo _link($article)->set('.link.link_box')->text(         
    		$imageHtml.$textHtml
	      	
		);
        if($lien != ''){
		echo '<span class="navigationWrapper navigationBottom">'.
	     	'<ul class="elements">'.
      			'<li class="element first last">'.
      				_link($article->getSection())->text($lien.' '.$article->getRubriquePageTitle()).  // 2170  (415)
      			'</li>'.
      		'</ul>'.
		'</span>';
        };
    	echo '</li>';
    	$i++;
    }
	
    //fermeture du listing
    echo _close('ul.elements');
} else {
    echo debugTools::infoDebug(array(__('fil actualites') => __('No entries')),'warning');
}
 
