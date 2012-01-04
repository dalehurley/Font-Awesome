<?php

$xml = sfConfig::get('app_rep-local') .
        $article->getSection()->getRubrique() .
        '/' .
        $article->getSection() .
        '/' .
        $article->filename .
        '.xml';
$xsl = dm::getDir() . '/dmCorePlugin/plugins/sidWidgetBePlugin/lib/xsl/' . sfConfig::get('app_xsl-article');


$section = $article->getSectionPageTitle();
$rubrique = $article->getRubriquePageTitle();

$return = '';

$doc_xml = new DOMDocument();
if ($doc_xml->load($xml)) {
    // Je charge en mï¿½moire mon document XSL
    // vérification des fichiers xsl
    if (!is_file($xsl)) {
        echo debugTools::infoDebug(array(__('Error : missed file') => $xsl), 'warning');
    }
    $return .= _tag('h2.title', $rubrique . ' - ' . $section);

    $return .= '<article itemscope itemtype="http://schema.org/Article">';

    $return .= _tag('h3.title itemprop="name"', $article->title);

    $doc_xsl = new DOMDocument();
    $doc_xsl->load($xsl);
    $moteurXslt = new xsltProcessor();
    $moteurXslt->importstylesheet($doc_xsl);

    // afficher l'image du xsl
    //$moteurXslt->setParameter('', 'imageAffiche', 'true');
    
    $return .= $moteurXslt->transformToXML($doc_xml);


    $sections = $doc_xml->getElementsByTagName("Section");
    $linkedArticles = array();

    foreach ($sections as $section) {
        $AssociatedWiths = $section->getElementsByTagName("AssociatedWith");
        foreach ($AssociatedWiths as $AssociatedWith) {
            $linkedArticles[] = (isset($AssociatedWith->getElementsByTagName("Reference")->item(0)->nodeValue)) ? $AssociatedWith->getElementsByTagName("Reference")->item(0)->nodeValue : "";
        }
    }

    echo $return; // on affiche les titres et chapeau de l'article principal du dossier avant d'afficher tous les sous articles puis de fermer la balise article
        //  affichage brut des articles
        foreach ($linkedArticles as $linkedArticle) {
            $linkedSidArticle = Doctrine_Core::getTable('SidArticle')->findOneByFilenameAndSectionId($linkedArticle, $article->sectionId);
            echo debugTools::infoDebug(array('ID LEA' => $linkedArticle.' - '.$article->sectionId));
            // affichage du texte de l'article avec le xsl
            include_partial('article/showArticleInDossier', array('article' => $linkedSidArticle));
        }


// on ferme l'article principal du dossier
    echo _close('article');
} else {
    $return = 'ERREUR : XML invalide :' . $xml;
}
?>
