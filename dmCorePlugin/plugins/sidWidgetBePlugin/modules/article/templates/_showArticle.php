<?php
/**
 * Retourne un article d'actualité xml formaté par le XSL, en html
 */
if(dmConfig::get('site_theme_version') == 'v1'){
	use_helper('Date');

	//récupération de la section et de la rubrique
	$section = $article->getSectionPageTitle();
	$rubrique = $article->getRubriquePagetitle();
	//composition de la catégorie de l'article
	$articleSection = $rubrique . ' - ' . $section;

	//ciblage XML et XSL
	$xml = sfConfig::get('app_rep-local') .
	        $article->getSection()->getRubrique() .
	        '/' .
	        $article->getSection() .
	        '/' .
	        $article->filename .
	        '.xml';
	$xsl = dm::getDir() . '/dmCorePlugin/plugins/sidWidgetBePlugin/lib/xsl/' . sfConfig::get('app_xsl-article');

	// vérification du fichier XSL
	if (!is_file($xsl)) $html.= debugTools::infoDebug(array(__('Error : missed file') => $xsl),'error');

	// vérification du fichier XML
	if (!is_file($xml)) {
		$html.= debugTools::infoDebug(array(__('Error : missed file') => $xml),'error');
	} else {
		
		//titre du contenu
		if($articleSection) echo '<h2 class="title">'.$articleSection.'</h2>';

		//création du parser XML
		$doc_xml = new DOMDocument();
		//ouverture du document XML
		if ($doc_xml->load($xml)) {
			//récupération du contenu du XML
			$doc_xsl = new DOMDocument();
			$doc_xsl->load($xsl);
			$moteurXslt = new xsltProcessor();
			$moteurXslt->importstylesheet($doc_xsl);

	                if ($article->getSection()->getRubrique() == 'aga_profession') {
	                    //récupération de l'image au dossier affiché
	                    $multimediaImage = '';
	                    $multimediaInserts = $doc_xml->getElementsByTagName("MultimediaInserts");
	                    if (count($multimediaInserts) > 0) {
	                        $multimediaImages = $multimediaInserts->item(0)->getElementsByTagName('MultimediaInsert');
	                        foreach ($multimediaImages as $multimediaImage) {
	                            $multimediaImage = $multimediaImages->item(0)->getElementsByTagName('FileName')->item(0)->nodeValue;
	                            $imageLink = '/_images/images' . $multimediaImage;
	//                            if (strpos($multimediaImage, '-g.jpg')){
	//                                break;
	//                            }
	                        }
	                    }
	                }
	                else {
	                    //affichage du contenu
	                    $imageLink = '/_images/lea' . $article->filename . '-g.jpg';
	                }
			$imageHtml = '';
			if (is_file(sfConfig::get('sf_web_dir').$imageLink) && $withImage){
				$imageHtml = 	'<div class="imageFullWrapper">'.
							    	'<img width="'.$widthImage.'" src="'.$imageLink.'" itemprop="image" class="image" alt="'.$article->title.'">'.
								'</div>';
			}
				echo '<article itemtype="http://schema.org/Article" itemscope="itemscope" class="itemscope Article">';
					echo '<header class="contentHeader">';
						echo $imageHtml;
						echo '<h1 itemprop="name" class="title itemprop name">'.$article->title.'</h1>';
						echo '<meta content="'.$articleSection.'" itemprop="articleSection">';
	                                        // Suppression du chapeau pour les articles AGENDA
	                                        if(strtoupper(sfConfig::get('app_article-data-type-agenda')) != ($doc_xml->getElementsByTagName('DataType')->item(0)->nodeValue)){
						echo '<span itemprop="description" class="teaser itemprop description">'.$article->getChapeau().'</span>';
	                                        };
	                                        // Suppression de la date de création de l'article pour les pages AGENDA
	                                        if(strtoupper(sfConfig::get('app_article-data-type-agenda')) != ($doc_xml->getElementsByTagName('DataType')->item(0)->nodeValue)){
						echo '<span class="date">'.__('published on').' ';
							echo '<time itemprop="datePublished" class="datePublished" pubdate="pubdate" datetime="'.$article->updated_at.'">'.format_date($article->updated_at, 'D').'</time>';
						echo '</span>';
	                                        };
					echo '</header>';
					echo '<section itemprop="articleBody" class="contentBody">';
						echo $moteurXslt->transformToXML($doc_xml);
					echo '</section>';
	                                // Suppression de la date si on est sur une page AGENDA
	                                if(strtoupper(sfConfig::get('app_article-data-type-agenda')) != ($doc_xml->getElementsByTagName('DataType')->item(0)->nodeValue)){
					echo '<footer class="contentFooter">';
						echo '<span class="meta">';
							echo '<span class="date">'.__('Article published on').' ';
								echo '<time itemprop="datePublished" class="datePublished" pubdate="pubdate" datetime="'.$article->updated_at.'">'.format_date($article->updated_at, 'd').'</time>';
							echo '</span>';
							echo '<span class="dash">&nbsp;-&nbsp;</span>';
							echo '<span class="copyright">&copy;&nbsp;';
								echo '<span itemprop="copyrightHolder" class="itemprop copyrightHolder">'.sfConfig::get('app_copyright-holder').'</span>';
								echo '<span class="dash">&nbsp;-&nbsp;</span>';
								echo '<span itemprop="copyrightYear" class="itemprop copyrightYear">'.substr(format_date($article->updated_at, 'y '), 0, 4).'</span>';
							echo '</span>';
						echo '</span>';
					echo '</footer>';
	                                };
				echo '</article>';

		} else {
			echo debugTools::infoDebug(array(__('Error : invalid xml') => $xml),'warning');
		}
	/*
		if (isset($articleList)){ // si on est dans le dataType agenda alors on a une liste d'articles de la meme section

			echo '<br /><h4 class="title">'.__('The other dates of the month').'</h4>';
			//ouverture du listing
			echo _open('ul.elements');

			$i = 0;
			$i_max = count($articleList); // il faut compter le nombre de resultats pour la page en cours, count($articlePager) renvoie la taille complète du pager	

			foreach ($articleList as $article) {
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
				if (is_file(sfConfig::get('sf_web_dir').$imageLink) && $i < 4 ){  // les 3 premiers articles ont une image
					$imageHtml = 	
						'<span class="imageWrapper">'.
							'<img src="'.$imageLink.'" itemprop="image" class="image" alt="'.$article->title.'">'.
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
								'<meta content="'.$article->created_at.'" itemprop="datePublished">'.
							'</span>'.
							'<span itemprop="description" class="teaser itemprop description">'.$article->getChapeau().'</span>'.
						'</span>'
				);
				echo '</li>';

			}

			//fermeture du listing
			echo _close('ul.elements');

		}
	*/


	}
}
elseif(dmConfig::get('site_theme_version') == 'v2'){
	use_helper('Date');

	//récupération de la section et de la rubrique
	$section = $article->getSectionPageTitle();
	$rubrique = $article->getRubriquePagetitle();
	//composition de la catégorie de l'article
	$articleSection = $rubrique . ' - ' . $section;

	//ciblage XML et XSL
	$xml = sfConfig::get('app_rep-local') .
	        $article->getSection()->getRubrique() .
	        '/' .
	        $article->getSection() .
	        '/' .
	        $article->filename .
	        '.xml';
	$xsl = dm::getDir() . '/dmCorePlugin/plugins/sidWidgetBePlugin/lib/xsl/' . sfConfig::get('app_xsl-article');

	// vérification du fichier XSL
	if (!is_file($xsl)) $html.= debugTools::infoDebug(array(__('Error : missed file') => $xsl),'error');

	// vérification du fichier XML
	if (!is_file($xml)) {
		$html.= debugTools::infoDebug(array(__('Error : missed file') => $xml),'error');
	} else {
		
		//titre du contenu
		if($articleSection) echo '<h2>'.$articleSection.'</h2>';

		//création du parser XML
		$doc_xml = new DOMDocument();
		//ouverture du document XML
		if ($doc_xml->load($xml)) {
			//récupération du contenu du XML
			$doc_xsl = new DOMDocument();
			$doc_xsl->load($xsl);
			$moteurXslt = new xsltProcessor();
			$moteurXslt->importstylesheet($doc_xsl);

	                if ($article->getSection()->getRubrique() == 'aga_profession') {
	                    //récupération de l'image au dossier affiché
	                    $multimediaImage = '';
	                    $multimediaInserts = $doc_xml->getElementsByTagName("MultimediaInserts");
	                    if (count($multimediaInserts) > 0) {
	                        $multimediaImages = $multimediaInserts->item(0)->getElementsByTagName('MultimediaInsert');
	                        foreach ($multimediaImages as $multimediaImage) {
	                            $multimediaImage = $multimediaImages->item(0)->getElementsByTagName('FileName')->item(0)->nodeValue;
	                            $imageLink = '/_images/images' . $multimediaImage;
	//                            if (strpos($multimediaImage, '-g.jpg')){
	//                                break;
	//                            }
	                        }
	                    }
	                }
	                else {
	                    //affichage du contenu
	                    $imageLink = '/_images/lea' . $article->filename . '-g.jpg';
	                }
			$imageHtml = '';
			if (is_file(sfConfig::get('sf_web_dir').$imageLink) && $withImage){
				$imageHtml ='<img width="'.$widthImage.'" src="'.$imageLink.'" alt="'.$article->title.'">';
			}
				echo '<article>';
					echo '<header>';
						echo $imageHtml;
						echo '<h1>'.$article->title.'</h1>';
                        // Suppression du chapeau pour les articles AGENDA
                        if(strtoupper(sfConfig::get('app_article-data-type-agenda')) != ($doc_xml->getElementsByTagName('DataType')->item(0)->nodeValue)){
							echo '<p><strong>'.$article->getChapeau().'</strong></p>';
                        };
                        // Suppression de la date de création de l'article pour les pages AGENDA
                        if(strtoupper(sfConfig::get('app_article-data-type-agenda')) != ($doc_xml->getElementsByTagName('DataType')->item(0)->nodeValue)){
							echo '<p><em>'.__('published on').' ';
								echo '<time>'.format_date($article->updated_at, 'D').'</time>';
							echo '</em></p>';
                        };
					echo '</header>';
					echo '<section>';
						echo $moteurXslt->transformToXML($doc_xml);
					echo '</section>';
                    // Suppression de la date si on est sur une page AGENDA
                    if(strtoupper(sfConfig::get('app_article-data-type-agenda')) != ($doc_xml->getElementsByTagName('DataType')->item(0)->nodeValue)){
					echo '<footer>';
							echo '<span>'.__('Article published on').' ';
								echo '<time>'.format_date($article->updated_at, 'd').'</time>';
							echo '</span>';
							echo '<span>&nbsp;-&nbsp;</span>';
							echo '<span>&copy;&nbsp;';
								echo '<span>'.sfConfig::get('app_copyright-holder').'</span>';
								echo '<span>&nbsp;-&nbsp;</span>';
								echo '<span>'.substr(format_date($article->updated_at, 'y '), 0, 4).'</span>';
							echo '</span>';
					echo '</footer>';
	                };
				echo '</article>';

		} else {
			echo debugTools::infoDebug(array(__('Error : invalid xml') => $xml),'warning');
		}

	}
}
