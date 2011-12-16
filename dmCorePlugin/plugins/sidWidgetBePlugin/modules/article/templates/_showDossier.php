<?php

$xml = sfConfig::get('app_rep-local') .
        $article->getSection()->getRubrique() .
        '/' .
        $article->getSection() .
        '/' .
        $article->filename .
        '.xml';
$xsl = dm::getDir() . '/dmCorePlugin/plugins/sidWidgetBePlugin/lib/xsl/' . sfConfig::get('app_xsl-article');


$section = $article->getSectionPageName();
$rubrique = $article->getRubriquePageName();

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
// mode d'affichage des sous-articles d'un dossier
    $modAffichage = sfconfig::get('app_mod-affichage-article-dossier', 'link');
    if ($modAffichage == 'raw') {
        //  affichage brut des articles
        foreach ($linkedArticles as $linkedArticle) {
            $linkedSidArticle = Doctrine_Core::getTable('SidArticle')->findOneByFilename($linkedArticle);
            echo debugTools::infoDebug(array('ID LEA' => $linkedArticle));
            // affichage du texte de l'article avec le xsl
            include_partial('article/showArticleInDossier', array('article' => $linkedSidArticle));
        }
    } elseif ($modAffichage == 'link') {
        // affichage de liens vers les articles connexes
        echo _open('ul.elements');
        foreach ($linkedArticles as $linkedArticle) {
            $linkedSidArticle = Doctrine_Core::getTable('SidArticle')->findOneByFilename($linkedArticle);
            echo _open('li.element');
            echo _link($linkedSidArticle)
                    ->text(_tag('span.wrapper', _tag('span.title', $linkedSidArticle)))->set('.link_box');
            echo _close('li');
        }
        echo _close('ul');
    }

// on ferme l'article principal du dossier
    echo _close('article');
} else {
    $return = 'ERREUR : XML invalide :' . $xml;
}
?>
