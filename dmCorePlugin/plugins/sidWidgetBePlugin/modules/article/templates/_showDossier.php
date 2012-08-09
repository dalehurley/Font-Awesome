<?php
/**
 * Retourne un article de dossier xml formaté par le XSL, en html
 */
if(dmConfig::get('site_theme_version') == 'v1'){
	use_helper('Date');
	$nbArticleOk=0;
	//récupération de la section et de la rubrique
	$section = $article->getSectionPageTitle();
	$rubrique = $article->getRubriquePageTitle();
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
	if (!is_file($xsl)) $html.= debugTools::infoDebug(array(__('Error : missed file') => $xsl),'warning');

	// vérification du fichier XML
	if (!is_file($xml)) {
		$html.= debugTools::infoDebug(array(__('Error : missed file') => $xml),'warning');
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
			
			//création du contenu à afficher
			$articleBody = $moteurXslt->transformToXML($doc_xml);

			//récupération des articles associées au dossier affiché
			$sections = $doc_xml->getElementsByTagName("Section");
			$linkedArticles = array();
	                
	                //récupération de l'image au dossier affiché
	                $multimediaImage = '';
			$multimediaInserts = $doc_xml->getElementsByTagName("MultimediaInserts");
	                if (count($multimediaInserts) > 0) {
	                    $multimediaImages = $multimediaInserts->item(0)->getElementsByTagName('MultimediaInsert');
	                    foreach ($multimediaImages as $multimediaImage) {
	                        $multimediaImage = $multimediaImages->item(0)->getElementsByTagName('FileName')->item(0)->nodeValue;
	                        if (strpos($multimediaImage, '-g.jpg'))
	                            break;
	                    }
	                }

			//remplissage d'un tableau de valeur contenant les id des articles associés
			foreach ($sections as $section) {
				$AssociatedWiths = $section->getElementsByTagName("AssociatedWith");
				foreach ($AssociatedWiths as $AssociatedWith) {
					if(isset($AssociatedWith->getElementsByTagName("Reference")->item(0)->nodeValue)) {
	                                    $linkedArticles[] = $AssociatedWith->getElementsByTagName("Reference")->item(0)->nodeValue;
	                                }
				}
			}
			
			//on ne récupère le contenu des articles associés que si un ou plusieurs articles est détecté
			if(count($linkedArticles) >= 1) {
				
				//inclusion du contenu dans un supWrapper
				$articleBody = _tag('section.supWrapper.first', $articleBody);
				
				//compteur (rajout de 1 car l'intro a déjà un container)
				$count = 1;
				$maxCount = count($linkedArticles) + 1;
				
				// compteur d'article ok
				$nbArticleOk = 0;

				//création d'un tableau de liens à afficher
				$elements = array();
				
				//récupération des articles et ajout à l'intérieur du contenu
				foreach ($linkedArticles as $linkedArticle) {
					//incrémentation compteur
					$count++;
					
					//récupérarion du nom de fichier de l'article en question directement dans la base
					$linkedSidArticle = Doctrine_Core::getTable('SidArticle')->findOneByFilenameAndSectionId($linkedArticle, $article->sectionId);
					if (is_object($linkedSidArticle) && $linkedSidArticle->id !=''){			
						//remplissage du tableau de navigation
						$elements[] = array('title' => $linkedSidArticle->title, 'anchor' => dmString::slugify($linkedSidArticle.'-'.$linkedSidArticle->id));
					
						//ajout information de débug
						//$articleBody.= debugTools::infoDebug(array('ID LEA' => $linkedArticle, 'Section ID' => $article->sectionId));
						$articleBody.= get_partial('article/showArticleInDossier', array('article' => $linkedSidArticle, 'count' => $count, 'maxCount' => $maxCount));
						$nbArticleOk++;
					}
				}
			}
			// si pas d'article fils présent alors on vide $articleBody
			if ($nbArticleOk==0) $articleBody = '';
			//insertion du contenu
			// $articleBody : le texte de l'article père + les articles fils à la suite
			// $elements    : la liste des articles fils avec un lien anchor sur la page

			// afficahge de la navigation des articles fils
			$articleFilsNavigation = '';
			if (isset($elements) && count($elements)){
				
				$articleFilsNavigation =
							'<div class="navigationWrapper navigationTop">'.
								'<ul class="elements">';

				$i = 0;
				$i_max = count($elements);							
				
				foreach ($elements as $element) {
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
	                        
	                        $articleFilsNavigation .=
									'<li class="element'.$position.'">'._link($article)->text($element['title'])->set('.link_box')->anchor($element['anchor']).'</li>';
				}
				$articleFilsNavigation .=
								'</ul>'.
							'</div>';
				}
			
			//affichage du contenu
	                //
	                // vérification du nom de l'image en vérifiant avec le nom de l'image dans le xml et/ou avec le prefixe 'images' ???
	                $imageExist = false;
	                $imageLink = '/_images/' . $multimediaImage;
	                if (is_file(sfConfig::get('sf_web_dir') . $imageLink)) {
	                    $imageExist = true;
	                } elseif (!is_file(sfConfig::get('sf_web_dir') . $imageLink)) {
	                    $imageLink = '/_images/images' . $multimediaImage;
	                    if (is_file(sfConfig::get('sf_web_dir') . $imageLink)) {
	                        $imageExist = true;
	                    }
	                }
	                else
	                    $imageExist = false;
	                
	                
			$imageHtml = '';
			if ($imageExist && $withImage){
				$imageHtml = 	'<div class="imageFullWrapper">'.
							    	//'<img width="'.$widthImage.'" src="'.$imageLink.'" itemprop="image" class="image" alt="'.$article->title.'">'.
							    	'<img src="'.$imageLink.'" itemprop="image" class="image" alt="'.$article->title.'">'.
								'</div>';
			}
				echo '<article itemtype="http://schema.org/Article" itemscope="itemscope" class="itemscope Article">';
					echo '<header class="contentHeader">';
						echo $imageHtml;
						echo '<h1 itemprop="name" class="title itemprop name">'.$article->title.'</h1>';
						echo '<meta content="'.$articleSection.'" itemprop="articleSection">';
						echo '<span itemprop="description" class="teaser itemprop description">'.$article->getChapeau().'</span>';
						echo '<span class="date">'.__('published on').' ';
							echo '<time itemprop="datePublished" class="datePublished" pubdate="pubdate" datetime="'.$article->updated_at.'">'.format_date($article->updated_at, 'D').'</time>';
						echo '</span>';
						echo $articleFilsNavigation;
					echo '</header>';
					echo '<section itemprop="articleBody" class="contentBody">';
						echo $articleBody;
					echo '</section>';
					echo '<footer class="contentFooter">';
						echo '<span class="meta">';
							echo '<span class="date">'.__('Article published on').' ';
								echo '<time itemprop="datePublished" class="datePublished" pubdate="pubdate" datetime="'.$article->updated_at.'">'.format_date($article->updated_at, 'd').'</time>';
							echo '</span>';
							echo '<span class="dash">&nbsp;-&nbsp;</span>';
							echo '<span class="copyright">&copy;&nbsp;';
								echo '<span itemprop="copyrightHolder" class="itemprop copyrightHolder">'.sfConfig::get('app_copyright-holder').'</span>';
								echo '<span class="dash">&nbsp;-&nbsp;</span>';
								echo '<span itemprop="copyrightYear" class="itemprop copyrightYear">'.format_date($article->updated_at, 'y ').'</span>';
							echo '</span>';
						echo '</span>';
					echo '</footer>';
				echo '</article>';
		} else {
			echo debugTools::infoDebug(array(__('Error : invalid xml') => $xml),'warning');
		}
	}
}
elseif(dmConfig::get('site_theme_version') == 'v2'){
	use_helper('Date');
	$nbArticleOk=0;
	//récupération de la section et de la rubrique
	$section = $article->getSectionPageTitle();
	$rubrique = $article->getRubriquePageTitle();
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
	if (!is_file($xsl)) $html.= debugTools::infoDebug(array(__('Error : missed file') => $xsl),'warning');

	// vérification du fichier XML
	if (!is_file($xml)) {
		$html.= debugTools::infoDebug(array(__('Error : missed file') => $xml),'warning');
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
			
			//création du contenu à afficher
			$articleBody = $moteurXslt->transformToXML($doc_xml);

			//récupération des articles associées au dossier affiché
			$sections = $doc_xml->getElementsByTagName("Section");
			$linkedArticles = array();
	                
	                //récupération de l'image au dossier affiché
	                $multimediaImage = '';
			$multimediaInserts = $doc_xml->getElementsByTagName("MultimediaInserts");
	                if (count($multimediaInserts) > 0) {
	                    $multimediaImages = $multimediaInserts->item(0)->getElementsByTagName('MultimediaInsert');
	                    foreach ($multimediaImages as $multimediaImage) {
	                        $multimediaImage = $multimediaImages->item(0)->getElementsByTagName('FileName')->item(0)->nodeValue;
	                        if (strpos($multimediaImage, '-g.jpg'))
	                            break;
	                    }
	                }

			//remplissage d'un tableau de valeur contenant les id des articles associés
			foreach ($sections as $section) {
				$AssociatedWiths = $section->getElementsByTagName("AssociatedWith");
				foreach ($AssociatedWiths as $AssociatedWith) {
					if(isset($AssociatedWith->getElementsByTagName("Reference")->item(0)->nodeValue)) {
	                                    $linkedArticles[] = $AssociatedWith->getElementsByTagName("Reference")->item(0)->nodeValue;
	                                }
				}
			}
			
			//on ne récupère le contenu des articles associés que si un ou plusieurs articles est détecté
			if(count($linkedArticles) >= 1) {
				
				//inclusion du contenu dans un supWrapper
				//$articleBody = _tag('section.supWrapper.first', $articleBody);
				
				//compteur (rajout de 1 car l'intro a déjà un container)
				$count = 1;
				$maxCount = count($linkedArticles) + 1;
				
				// compteur d'article ok
				$nbArticleOk = 0;

				//création d'un tableau de liens à afficher
				$elements = array();
				
				//récupération des articles et ajout à l'intérieur du contenu
				foreach ($linkedArticles as $linkedArticle) {
					//incrémentation compteur
					$count++;
					
					//récupérarion du nom de fichier de l'article en question directement dans la base
					$linkedSidArticle = Doctrine_Core::getTable('SidArticle')->findOneByFilenameAndSectionId($linkedArticle, $article->sectionId);
					if (is_object($linkedSidArticle) && $linkedSidArticle->id !=''){			
						//remplissage du tableau de navigation
						$elements[] = array('title' => $linkedSidArticle->title, 'anchor' => dmString::slugify($linkedSidArticle.'-'.$linkedSidArticle->id));
					
						//ajout information de débug
						//$articleBody.= debugTools::infoDebug(array('ID LEA' => $linkedArticle, 'Section ID' => $article->sectionId));
						$articleBody.= get_partial('article/showArticleInDossier', array('article' => $linkedSidArticle, 'count' => $count, 'maxCount' => $maxCount));
						if($count < $maxCount) $articleBody.= '<hr>';
						$nbArticleOk++;
					}
				}
			}
			// si pas d'article fils présent alors on vide $articleBody
			if ($nbArticleOk==0) $articleBody = '';
			//insertion du contenu
			// $articleBody : le texte de l'article père + les articles fils à la suite
			// $elements    : la liste des articles fils avec un lien anchor sur la page

			// afficahge de la navigation des articles fils
			$articleFilsNavigation = '';
			if (isset($elements) && count($elements)){
				
				$articleFilsNavigation =
								'<ul>';

				$i = 0;
				$i_max = count($elements);							
				
				foreach ($elements as $element) {
					$i++;
			    	$position = '';
			        switch ($i){
			            case '1' : 
			            	if ($i_max == 1) $position = 'class="first last"';
			            	else $position = 'class="first"';
			                break;
			            default : 
			            	if ($i == $i_max) $position = 'class="last"';
			            	else $position = '';
			            	break;
			        }
	                        
	                        $articleFilsNavigation .=
									'<li '.$position.'>'._link($article)->text($element['title'])->set('.link_box')->anchor($element['anchor']).'</li>';
				}
				$articleFilsNavigation .=
								'</ul>';
				}
			
			//affichage du contenu
	                //
	                // vérification du nom de l'image en vérifiant avec le nom de l'image dans le xml et/ou avec le prefixe 'images' ???
	                $imageExist = false;
	                $imageLink = '/_images/' . $multimediaImage;
	                if (is_file(sfConfig::get('sf_web_dir') . $imageLink)) {
	                    $imageExist = true;
	                } elseif (!is_file(sfConfig::get('sf_web_dir') . $imageLink)) {
	                    $imageLink = '/_images/images' . $multimediaImage;
	                    if (is_file(sfConfig::get('sf_web_dir') . $imageLink)) {
	                        $imageExist = true;
	                    }
	                }
	                else
	                    $imageExist = false;
	                
	                
			$imageHtml = '';
			if ($imageExist && $withImage){
				$imageHtml = 	'<img src="'.$imageLink.'" alt="'.$article->title.'">';
			}
				//echo '<article itemtype="http://schema.org/Article" itemscope="itemscope" class="itemscope Article">';
					echo '<div>';
						echo $imageHtml;
						echo '<h1>'.$article->title.'</h1>';
						echo '<p>'.$article->getChapeau().'</p><br />';
						echo '<em>'.__('published on').' '.format_date($article->updated_at, 'D').'</em>';
						echo $articleFilsNavigation;
					echo '</div>';
					echo '<div>';
						echo $articleBody;
					echo '</div>';
					echo '<p>'.__('Article published on').' '.format_date($article->updated_at, 'd').'&nbsp;-&nbsp;&copy;&nbsp;'.sfConfig::get('app_copyright-holder').'&nbsp;-&nbsp;'.format_date($article->updated_at, 'y ');
					
				//echo '</article>';
		} else {
			echo debugTools::infoDebug(array(__('Error : invalid xml') => $xml),'warning');
		}
	}
}
