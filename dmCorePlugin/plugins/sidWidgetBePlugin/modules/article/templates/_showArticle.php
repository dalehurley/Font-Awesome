<?php
/*
 * Retourne un article xml formaté par le XSL, en html
 */
$html = '';

//récupération des différentes variables par défault
$dash = _tag('span.dash', sfConfig::get('app_vars-partial_dash'));

//ciblage du XML
$xml = sfConfig::get('app_rep-local') .
        $article->getSection()->getRubrique() .
        '/' .
        $article->getSection() .
        '/' .
        $article->filename .
        '.xml';
$xsl = dm::getDir() . '/dmCorePlugin/plugins/sidWidgetBePlugin/lib/xsl/' . sfConfig::get('app_xsl-article');

// vérification du fichier XML
if (!is_file($xml)) $html.= debugTools::infoDebug(array(__('Error : missed file') => $xml),'warning');

// vérification des fichiers xsl
if (!is_file($xsl)) $html.= debugTools::infoDebug(array(__('Error : missed file') => $xsl),'warning');

//récupération de la section et de la rubrique
$section = $article->getSectionPageTitle();
$rubrique = $article->getRubriquePagetitle();

//titre du contenu
$html.= get_partial('global/titleWidget', array('title' => $rubrique . $dash . $section, 'isContainer' => true));

//création du parser XML
$doc_xml = new DOMDocument();

//ouverture du document XML
if ($doc_xml->load($xml)) {
	
	//récupération du contenu du XML
	$doc_xsl = new DOMDocument();
    $doc_xsl->load($xsl);
    $moteurXslt = new xsltProcessor();
    $moteurXslt->importstylesheet($doc_xsl);
	
	//affichage du contenu
	$articleOpts = array(
						'container' => 'article',
						'name' => $article->title,
						'description' => $article->getChapeau(),
						'image' => '/_images/lea' . $article->filename . '-g.jpg',
						'dateCreated' => $article->created_at,
						'dateModified' => $article->updated_at,
						'articleBody' => $moteurXslt->transformToXML($doc_xml),
					);
	
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', $articleOpts);
	
	/*
    $return .= _tag('h2.title', $rubrique . ' - ' . $section);
    $return .= '<article itemscope itemtype="http://schema.org/Article">';

    //lien vers l'image
    $imgLink = '/_images/lea' . $article->filename . '-g.jpg';
    //on vérifie que l'image existe
    $imgExist = is_file(sfConfig::get('sf_web_dir') . $imgLink);

    // on teste si le fichier image est présent sur le serveur avec son chemin absolu
    if ($imgExist) {
        $return .= _open('div.imageFullWrapper');
        $return .= _media($imgLink)
                ->set('.image itemprop="image"')
                ->alt($article->getTitle())
                //redimenssionnement propre lorsque l'image sera en bibliothèque
                ->width(spLessCss::gridGetContentWidth());
        //->height(spLessCss::gridGetHeight(14,0))
        $return .= _close('div');
    }

    $return .= _tag('h2.title itemprop="name"', $article->title);

    
    
    $return .= $moteurXslt->transformToXML($doc_xml);

//Fermeture de l'article
    $return .= _close('article');
	 */
} else {
    //$return = 'ERREUR : XML invalide :' . $xml;
}

//affichage html en sortie
echo $html;