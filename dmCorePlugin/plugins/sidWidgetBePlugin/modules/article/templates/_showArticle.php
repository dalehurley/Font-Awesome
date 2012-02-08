<?php
//html de sortie
$html = '';

//récupération des différentes variables par défault
$dash = _tag('span.dash', sfConfig::get('app_vars-partial_dash'));

//ciblage XML et XSL
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
						'copyrightHolder' => 'SID Presse',							//en attendant implémentation dans base depuis la valeur du XML
						'copyrightYear' => substr($article->created_at, 0, 4)		//en attendant implémentation dans base depuis la valeur du XML
					);
	
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', $articleOpts);
	
} else {
	$html.= debugTools::infoDebug(array(__('Error : invalid xml') => $xml),'warning');
}

//affichage html en sortie
echo $html;