<?php // Vars: $articlePager, $parent, $route, $header

$html = '';

if(count($articlePager)){
    
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
                if ($imageExist) {
                    $imageLink = $imageLink;
                }
            }
else{
	
	$imageLink = '/_images/lea' . $article->filename . '-p.jpg';
}
	$imageHtml = '';
        //on supprime les photos après les 3 premiers articles
	if (is_file(sfConfig::get('sf_web_dir').$imageLink) && $i < 4 ){  // les 3 premiers articles ont une image
		$imageHtml = 	
			'<span class="imageWrapper">'.
				'<img src="'.$imageLink.'" width="260" itemprop="image" class="image" alt="'.$article->getTitle().'">'.
			'</span>';
	}

	//ajout de l'article
	echo 
	'<li itemtype="http://schema.org/Article" itemscope="itemscope" class="element itemscope Article'.$position.'">';
	echo _link($article)->set('.link.link_box')->text(
			$imageHtml.
			'<span class="wrapper">'.
				'<span class="subWrapper">'.
					'<span itemprop="name" class="title itemprop name">'.$article->getTitle().'</span>'.
					'<meta content="'.$article->createdAt.'" itemprop="datePublished">'.
				'</span>'.
				'<span itemprop="description" class="teaser itemprop description">'.$article->getChapeau().'</span>'.
			'</span>'
	);
	echo '</li>';

}

//fermeture du listing
echo _close('ul.elements');

echo _tag('div.navigation.navigationBottom', $articlePager->renderNavigationBottom());
    }
}