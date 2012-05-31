<?php
// Vars: $sitesUtilesPager

//récupération du titre
foreach ($sitesUtilesPager as $sitesUtiles) {
    $firstSitesUtilesGroup = $sitesUtiles->getGroupeSitesUtiles()->title;
    $firstDescriptionSiteUtileGroup = $sitesUtiles->getGroupeSitesUtiles()->getDescription();
    break;
}
echo get_partial('global/titleWidget', array('title' => $firstSitesUtilesGroup));

//affichage du pager du haut
echo get_partial('global/navigationWrapper', array('placement' => 'top', 'pager' => $sitesUtilesPager));
if($firstDescriptionSiteUtileGroup != ''){
//    echo _open('article.itemscope Article');
        echo _open('header.contentHeader');
            echo _open('span.wrapper');
                echo _tag('span.teaser', $firstDescriptionSiteUtileGroup);
            echo _close('span');
        echo _close('header');
//    echo _close('article');
}
//ouverture du listing
echo _open('ul.elements');

//compteur
// $count = 0;
// $maxCount = count($sitesUtilesPager);
$i = 0;
$i_max = count($sitesUtilesPager); // il faut compter le nombre de resultats pour la page en cours, count($articlePager) renvoie la taille complète du pager	

foreach ($sitesUtilesPager as $article) {
	//incrémentation compteur
	// $count++;
	
	//options de l'article
	// $articleOpt = array(
	// 				'name' => $sitesUtiles,
	// 				'description' => $sitesUtiles->description,
	// 				'image' => $sitesUtiles->getImage(),
	// 				'url' => $sitesUtiles->url,
	// 				'isUrlBlank' => true,
	// 				'count' => $count,
	// 				'maxCount' => $maxCount,
	// 				'container' => 'li.element',
	// 				'isListing' => true
	// 			);
	
	// //ajout de l'article
	// $html.= get_partial('global/schema/Thing/CreativeWork/Article', $articleOpt);

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


	$media = $article->getImage();
	$imageHtml = '';
	if (is_object($media)){  
		$imageHtml = 	
			'<span class="imageWrapper">'.
				//_media($media)->height('35px')->method('scale').
				_media($media)->height(80).
			'</span>';
	}

	//ajout de l'article
	echo 
	'<li itemtype="http://schema.org/Article" itemscope="itemscope" class="element itemscope Article'.$position.'">
<a class="link link_box" href="'.$article->getUrl().'" title="'.$article->getTitle().'">
	';
	echo 
			$imageHtml.
			'<span class="wrapper">'.
				'<span class="subWrapper">'.
					'<span itemprop="name" class="title itemprop name">'.$article->getTitle().'</span>'.
					'<meta content="'.$article->createdAt.'" itemprop="datePublished">'.
				'</span>'.
				'<span itemprop="description" class="teaser itemprop description">'.$article->description.'</span>'.
			'</span>'
	;
	echo '
</a>
	</li>';

}
	
//fermeture du listing
echo _close('ul.elements');

//affichage du pager du bas
echo get_partial('global/navigationWrapper', array('placement' => 'bottom', 'pager' => $sitesUtilesPager));

