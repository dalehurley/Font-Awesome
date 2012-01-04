<?php

/*
 * Retourne un article xml formaté par le XSL, en html
 */
$xml = sfConfig::get('app_rep-local') .
        $article->getSection()->getRubrique() .
        '/' .
        $article->getSection() .
        '/' .
        $article->filename .
        '.xml';
$xsl = dm::getDir() . '/dmCorePlugin/plugins/sidWidgetBePlugin/lib/xsl/' . sfConfig::get('app_xsl-article');

$return = '';

$section = $article->getSectionPageTitle();
$rubrique = $article->getRubriquePagetitle();

// vérification des fichiers xml
if (!is_file($xml)) {
    echo debugTools::infoDebug(array(__('Error : missed file') => $xml),'warning');
}

$doc_xml = new DOMDocument();
if ($doc_xml->load($xml)) {
    // Je charge en mï¿½moire mon document XSL
    // vérification des fichiers xsl
    if (!is_file($xsl)) {
        echo debugTools::infoDebug(array(__('Error : missed file') => $xsl),'warning');
    }

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

    $doc_xsl = new DOMDocument();
    $doc_xsl->load($xsl);
    $moteurXslt = new xsltProcessor();
    $moteurXslt->importstylesheet($doc_xsl);
    
    $return .= $moteurXslt->transformToXML($doc_xml);

//Fermeture de l'article
    $return .= _close('article');
} else {
    $return = 'ERREUR : XML invalide :' . $xml;
}

echo $return;
?>
