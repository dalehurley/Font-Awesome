<?php
// Vars: $sitesUtilesPager

//récupération du titre
foreach ($sitesUtilesPager as $sitesUtiles) {
    $firstSitesUtilesGroup = $sitesUtiles->getGroupeSitesUtiles()->title;
    $firstDescriptionSiteUtileGroup = $sitesUtiles->getGroupeSitesUtiles()->getDescription();
    break;
}
if(dmConfig::get('site_theme_version') == 'v1'){
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
	if ($media->checkFileExists()){  
		$imageHtml = 	
			'<span class="imageWrapper">'.
				//_media($media)->height('35px')->method('scale').
				_media($media)->size(200, 80)->method('scale').
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
</a>';
        
            if($article->getFiles1()->checkFileExists() == true){
            echo _open('footer', array('class' => 'contentFooter'));
                echo _open('div', array('class' => 'fileWrapper'));
                    echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                    if($article->getTitleFile1() != NULL){
                    echo _link($article->getFiles1())->text($article->getTitleFile1());
                }
                else echo _link($article->getFiles1());
                echo _close('div');
            echo _close('footer');
            }
            if($article->getFiles2()->checkFileExists() == true){
            echo _open('footer', array('class' => 'contentFooter'));
                echo _open('div', array('class' => 'fileWrapper'));
                    echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                    if($article->getTitleFile2() != NULL){
                    echo _link($article->getFiles2())->text($article->getTitleFile2());
                }
                else echo _link($article->getFiles2());
                echo _close('div');
            echo _close('footer');
            }
            if($article->getFiles3()->checkFileExists() == true){
            echo _open('footer', array('class' => 'contentFooter'));
                echo _open('div', array('class' => 'fileWrapper'));
                    echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                    if($article->getTitleFile3() != NULL){
                    echo _link($article->getFiles3())->text($article->getTitleFile3());
                }
                else echo _link($article->getFiles3());
                echo _close('div');
            echo _close('footer');
            }
            if($article->getFiles4()->checkFileExists() == true){
            echo _open('footer', array('class' => 'contentFooter'));
                echo _open('div', array('class' => 'fileWrapper'));
                    echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                    if($article->getTitleFile4() != NULL){
                    echo _link($article->getFiles4())->text($article->getTitleFile4());
                }
                else echo _link($article->getFiles4());
                echo _close('div');
            echo _close('footer');
            }
            if($article->getFiles5()->checkFileExists() == true){
            echo _open('footer', array('class' => 'contentFooter'));
                echo _open('div', array('class' => 'fileWrapper'));
                    echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                    if($article->getTitleFile5() != NULL){
                    echo _link($article->getFiles5())->text($article->getTitleFile5());
                }
                else echo _link($article->getFiles5());
                echo _close('div');
            echo _close('footer');
            }
	echo '</li>';

}
	
//fermeture du listing
echo _close('ul.elements');

//affichage du pager du bas
echo get_partial('global/navigationWrapper', array('placement' => 'bottom', 'pager' => $sitesUtilesPager));
}

elseif(dmConfig::get('site_theme_version') == 'v2'){
    echo _tag('h2', $firstSitesUtilesGroup);
    echo $sitesUtilesPager->renderNavigationTop();
    if($firstDescriptionSiteUtileGroup != ''){
            echo _open('header');
                    echo _tag('p', $firstDescriptionSiteUtileGroup);
            echo _close('header');
    }
    echo _tag('hr');
    //ouverture du listing
    echo _open('ul');
    $i = 0;
    $i_max = count($sitesUtilesPager); // il faut compter le nombre de resultats pour la page en cours, count($articlePager) renvoie la taille complète du pager    

    foreach ($sitesUtilesPager as $article) {

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
        if ($media->checkFileExists()){
            $imageHtml .=  _media($media)->size(200, 80)->method('scale');
        }

        //ajout de l'article
        echo 
        '<li itemtype="http://schema.org/Article" itemscope="itemscope" class="thumbnails temscope Article'.$position.'">
            <a class="link thumbnail" href="'.$article->getUrl().'" title="'.$article->getTitle().'">';
        echo 
                $imageHtml.
                '<div class="caption">'.
                        '<h3 itemprop="name" class="itemprop name">'.$article->getTitle().'</h3>'.
                        '<meta content="'.$article->createdAt.'" itemprop="datePublished">'.
                    '<p itemprop="description" class="teaser itemprop description">'.$article->description.'</p>'.
                '</div>'
        ;
        echo '
            </a>';
            
                if($article->getFiles1()->checkFileExists() == true){
                echo _open('footer', array('class' => 'contentFooter'));
                    echo _open('div', array('class' => 'fileWrapper'));
                        echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                        if($article->getTitleFile1() != NULL){
                        echo _link($article->getFiles1())->text($article->getTitleFile1());
                    }
                    else echo _link($article->getFiles1());
                    echo _close('div');
                echo _close('footer');
                }
                if($article->getFiles2()->checkFileExists() == true){
                echo _open('footer', array('class' => 'contentFooter'));
                    echo _open('div', array('class' => 'fileWrapper'));
                        echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                        if($article->getTitleFile2() != NULL){
                        echo _link($article->getFiles2())->text($article->getTitleFile2());
                    }
                    else echo _link($article->getFiles2());
                    echo _close('div');
                echo _close('footer');
                }
                if($article->getFiles3()->checkFileExists() == true){
                echo _open('footer', array('class' => 'contentFooter'));
                    echo _open('div', array('class' => 'fileWrapper'));
                        echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                        if($article->getTitleFile3() != NULL){
                        echo _link($article->getFiles3())->text($article->getTitleFile3());
                    }
                    else echo _link($article->getFiles3());
                    echo _close('div');
                echo _close('footer');
                }
                if($article->getFiles4()->checkFileExists() == true){
                echo _open('footer', array('class' => 'contentFooter'));
                    echo _open('div', array('class' => 'fileWrapper'));
                        echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                        if($article->getTitleFile4() != NULL){
                        echo _link($article->getFiles4())->text($article->getTitleFile4());
                    }
                    else echo _link($article->getFiles4());
                    echo _close('div');
                echo _close('footer');
                }
                if($article->getFiles5()->checkFileExists() == true){
                echo _open('footer', array('class' => 'contentFooter'));
                    echo _open('div', array('class' => 'fileWrapper'));
                        echo _tag('h5', array('class' => 'title'), __('Download file, click the link below'));
                        if($article->getTitleFile5() != NULL){
                        echo _link($article->getFiles5())->text($article->getTitleFile5());
                    }
                    else echo _link($article->getFiles5());
                    echo _close('div');
                echo _close('footer');
                }
        echo '</li>';

    }
        
    //fermeture du listing
    echo _close('ul.elements');

    //affichage du pager du bas
    echo $sitesUtilesPager->renderNavigationBottom();
}

