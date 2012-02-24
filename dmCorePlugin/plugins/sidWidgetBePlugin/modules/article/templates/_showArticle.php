<?php
/**
 * Retourne un article d'actualité xml formaté par le XSL, en html
 */

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
/*
		//affichage du contenu
		$articleOpts = array(
							'container' => 'article',
							'name' => $article->title,
							'description' => $article->getChapeau(),
							'image' => '/_images/lea' . $article->filename . '-g.jpg',
							'dateCreated' => $article->created_at,
							'dateModified' => $article->updated_at,
							'articleSection' => $rubrique . ' - ' . $section,
							'articleBody' => $moteurXslt->transformToXML($doc_xml),
							'copyrightHolder' => 'SID Presse',							//en attendant implémentation dans base depuis la valeur du XML
							'copyrightYear' => substr($article->created_at, 0, 4)		//en attendant implémentation dans base depuis la valeur du XML
						);

		$html.= get_partial('global/schema/Thing/CreativeWork/Article', $articleOpts);
*/
		$imageLink = '/_images/lea' . $article->filename . '-g.jpg';
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
					echo '<span itemprop="description" class="teaser itemprop description">'.$article->getChapeau().'</span>';
					echo '<span class="date">'.__('Published on').' ';
						echo '<time itemprop="datePublished" class="datePublished" pubdate="pubdate" datetime="'.$article->created_at.'">'.format_date($article->created_at, 'D').'</time>';
					echo '</span>';
				echo '</header>';
				echo '<section itemprop="articleBody" class="contentBody">';
					echo $moteurXslt->transformToXML($doc_xml);
				echo '</section>';
				echo '<footer class="contentFooter">';
					echo '<span class="meta">';
						echo '<span class="date">'.__('Article published on').' ';
							echo '<time itemprop="datePublished" class="datePublished" pubdate="pubdate" datetime="'.$article->created_at.'">'.format_date($article->created_at, 'd').'</time>';
						echo '</span>';
						echo '<span class="dash">&nbsp;-&nbsp;</span>';
						echo '<span class="copyright">&copy;&nbsp;';
							echo '<span itemprop="copyrightHolder" class="itemprop copyrightHolder">'.sfConfig::get('app_copyright-holder').'</span>';
							echo '<span class="dash">&nbsp;-&nbsp;</span>';
							echo '<span itemprop="copyrightYear" class="itemprop copyrightYear">'.format_date($article->created_at, 'y ').'</span>';
						echo '</span>';
					echo '</span>';
				echo '</footer>';
			echo '</article>';

	} else {
		echo debugTools::infoDebug(array(__('Error : invalid xml') => $xml),'warning');
	}
}
