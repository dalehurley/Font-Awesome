
<?php
// vars : $section, $titreBloc, $titreLien, $longueurTexte, $articles, $arrayRubrique, $photo
$html = '';

if (count($articles)) { // si nous avons des actu articles
	
	echo '<h4 class="title">'.$titreBloc.'</h4>';
		
	//ouverture du listing
    echo _open('ul.elements');
	
	//compteur
	$count = 0;
	$maxCount = count($articles);
	
    foreach ($articles as $article) {

		// gestion de l'image    	
    	$imageFile = '/_images/lea' . $article->filename . '-p.jpg';
    	$imageHtml = '';
    	if (is_file(sfConfig::get('sf_web_dir').$imageFile)){
      	   	$imageHtml = '<span class="imageWrapper">'.
      	   		_media($imageFile)->width($widthImage)->set('.image itemprop="image"')->alt($article). 
	      	'</span>';
	    }
	    // affichage de l'article
    	echo '<li class="element itemscope Article first" itemscope="itemscope" itemtype="http://schema.org/Article">';
    	echo _link($article)->set('.link.link_box')->text(
    		$imageHtml.
	      	'<span class="wrapper">'.
	      		'<span class="subWrapper">'.
	      			'<span class="title itemprop name" itemprop="name">'.$article.'</span>'.
	      		'</span>'.
	      		'<span class="teaser itemprop description" itemprop="description">'.
		      		stringTools::str_truncate($article->getChapeau(), $longueurTexte, '(...)', true).
		      	'</span>'.
		    '</span>'
		);

	    echo '<ul class="elements">'.
      			'<li class="element first last">'.
      				'<span class="navigationWrapper navigationBottom">'.
      				_link($article->getSection())->set('.link')->text($titreLien.' '.$article->getRubriquePageTitle()).
      				'</span>'.
      			'</li>'.
      		'</ul>';

    	echo '</li>';


/*
		//création d'un tableau de liens à afficher
		$elements = array();
		$elements[] = array('title' => $titreLien . '&#160;' . $arrayRubrique[$article->filename], 'linkUrl' => $article->Section);
		
		echo get_partial('global/schema/Thing/CreativeWork/Article', array(
												'name' => $article->getTitle(),
												'description' => $article->getChapeau(),
												'image' => '/_images/lea' . $article->filename . '-p.jpg',
												'dateCreated' => $article->created_at,
												'isDateMeta' => true,
												'count' => $count,
												'maxCount' => $maxCount,
												'container' => 'li.element',
												'isListing' => true,
												'descriptionLength' => $longueurTexte,
												'navigationBottomElements' => $elements,
												'url' => $article
												));
*/


    }
	
    //fermeture du listing
    echo _close('ul.elements');
    
}



// MODIF POUR HELPER HTML
// echo _tag('h4.title','Actualités');
//        echo _open('ul.elements');
//            echo _open('li.element.itemscope.Article.first');
//                echo _link('/dev.php/rubriques/fiscal/actualites-6797/le-dispositif-du-controle-sur-demande-des-successions-et-donations-est-perennise')
//                    ->set('.link.link_box')
//                        ->title('Le dispositif du contrôle sur demande des successions et donations est pérennisé !')
//                        ->text(
//                            _tag('span.subWrapper',
//                                _tag('span.title.itemprop.name','Le dispositif du contrôle sur demande des successions et donations est pérennisé !').
//                                _tag('span.date','<time datetime="2012-01-04 13:55:00" pubdate="pubdate" class="datePublished" itemprop="datePublished">04/01/12</time>')
//                            ).
//                            _tag('span.teaser.itemprop.description','La procédure de contrôle sur demande des successions et donations, permettant de raccourcir le délai de<span class="ellipsis">&#160;(...)</span>')
//                        );
//                echo _open('span.navigation.navigationBottom');
//                    echo _open('ul.elements');
//                        echo _open('li.element.first.last');
//                            echo _link('/dev.php/rubriques/fiscal/actualites-6797')->set('.link')->text('L&rsquo;actualité en&#160;Fiscal');
//                        echo _close('li');
//                    echo _close('ul');
//                echo _close('span');
//            echo _close('li');
//            echo _open('li.element.itemscope.Article');
//            echo _link('/dev.php/rubriques/creation-d-entreprise/actualites/financement-de-la-creation-d-entreprise-oseo-et-la-banque-postale-s-allient')->set('.link.link_box')->title('Financement de la création d\'entreprise : Oseo et la Banque Postale s\'allient')->text(
//                    _tag('span.subWrapper',
//                        _tag('span.title.itemprop.name','Financement de la création d\'entreprise : Oseo et la Banque Postale s\'allient').
//                        _tag('span.date','(<time datetime="2012-01-04 12:00:00" pubdate="pubdate" class="datePublished" itemprop="datePublished">04/01/12</time>)')
//                    ).
//                    _tag('span.teaser.itemprop.description','Une bonne nouvelle pour les créateurs d\'entreprise en ce début d\'année ? Une nouvelle piste pour<span class="ellipsis">&#160;(...)</span>')
//                );
//                echo _open('span.navigation.navigationBottom');
//                    echo _open('ul.elements');
//                        echo _open('li.element.first.last');
//                            echo _link('/dev.php/rubriques/creation-d-entreprise/actualites')->set('.link')->text('L\'actualité en&#160;Création d\'entreprise');
//                        echo _close('li');
//                    echo _close('ul');
//                echo _close('span');
//            echo _close('li');
//              echo _open('li.element.itemscope.Article');
//            echo _link('/dev.php/rubriques/social/actualites/radiation-automatique-des-travailleurs-independants-sans-activite')->set('.link.link_box')->title('Radiation automatique des travailleurs indépendants sans activité')->text(
//                    _tag('span.subWrapper',
//                        _tag('span.title.itemprop.name','Radiation automatique des travailleurs indépendants sans activité').
//                        _tag('span.date','(<time datetime="2012-01-03 14:00:00" pubdate="pubdate" class="datePublished" itemprop="datePublished">03/01/12</time>)')
//                    ).
//                    _tag('span.teaser.itemprop.description','Les travailleurs indépendants qui n\'ont eu aucun chiffre d\'affaires pendant deux années civiles<span class="ellipsis">&#160;(...)</span>')
//                );
//                echo _open('span.navigation.navigationBottom');
//                    echo _open('ul.elements');
//                        echo _open('li.element.first.last');
//                            echo _link('/dev.php/rubriques/social/actualites')->set('.link')->text('L\'actualité en&#160;Social');
//                        echo _close('li');
//                    echo _close('ul');
//                echo _close('span');
//            echo _close('li');  
//           
//        echo _close('ul.elements');
//        
 // FOREACH SUR ARTICLE
 
// echo _tag('h4.title',$titreBloc);
//        echo _open('ul.elements');
//        foreach ($articles as $article) {
//            if(!is_file('/data/www/_lib/diem/install')){ echo 'toto<br />';};
//            echo _open('li.element.itemscope.Article.first');
//                echo _link($article)
//                    ->set('.link.link_box')
////                        ->title('Le dispositif du contrôle sur demande des successions et donations est pérennisé !')
//                        ->text(
//                            _tag('span.subWrapper',
//                                _tag('span.title.itemprop.name',$article).
//                                _tag('span.date',$article->created_at)
//                            ).
//                            _tag('span.teaser.itemprop.description',$article->getChapeau())
//                        );
//                echo _open('span.navigation.navigationBottom');
//                    echo _open('ul.elements');
//                        echo _open('li.element.first.last');
//                            echo _link($article->getSection()->getRubrique())->set('.link')->text($titreLien.'&#160;'.$arrayRubrique[$article->filename]);
//                        echo _close('li');
//                    echo _close('ul');
//                echo _close('span');
//            echo _close('li');
//        };
//        echo _close('ul.elements');
//}
