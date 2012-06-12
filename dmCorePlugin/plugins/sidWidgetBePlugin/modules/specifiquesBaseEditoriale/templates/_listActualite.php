<?php
//$vars =  $articles, $nbArticles, $titreBloc, $lien, $length, $chapo, $width, $height, $withImage
$html = '';
if(count($articles)){
//titre du contenu
echo '<h4 class="title">'.$titreBloc.'</h4>';



//ouverture du listing
echo _open('ul.elements');

$i = 0;
$i_max = count($articles); // il faut compter le nombre de resultats pour la page en cours, count($articlePager) renvoie la taille complète du pager	

foreach ($articles as $article) {
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


	//on supprime les photos après les 3 premiers articles
	$imageLink = '/_images/lea' . $article->filename . '-p.jpg';
	$imageHtml = '';
	if($withImage == TRUE){
		if (is_file(sfConfig::get('sf_web_dir').$imageLink) && $i < 4 ){  // les 3 premiers articles ont une image
			$imageHtml = 	
				'<span class="imageWrapper">'.
					'<img src="'.$imageLink.'" width="'.$width.'" itemprop="image" class="image" alt="'.$article->getTitle().'">'.
				'</span>';
		}
	}

	// gestion de l'affichage du titre seul ou non
	$chapeauHtml = '';
	$textHtml = '';
	if($justTitle != true) {
		$chapeauHtml = '<span itemprop="description" class="teaser itemprop description">'.stringTools::str_truncate($article->getChapeau(), $length, ' (...)',true).'</span>';
	}
	$textHtml = 
			'<span class="wrapper">'.
				'<span class="subWrapper">'.
					'<span itemprop="name" class="title itemprop name">'.$article->getTitle().'</span>'.
					'<meta content="'.$article->createdAt.'" itemprop="datePublished">'.
				'</span>'.
				$chapeauHtml.
			'</span>';

	
	//ajout de l'article
	echo 
	'<li itemtype="http://schema.org/Article" itemscope="itemscope" class="element itemscope Article'.$position.'">';
	echo _link($article)->set('.link.link_box')->text(
			$imageHtml.$textHtml
			
	);
	echo '</li>';

}

//fermeture du listing
echo _close('ul.elements');
}