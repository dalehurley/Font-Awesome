<?php

// Vars: $article
// Vars: $articleTags
// Vars: $rubrique
// Vars: $section

$xml = sfConfig::get('app_rep-local') .
        $article->getSection()->getRubrique() .
        '/' .
        $article->getSection() .
        '/' .
        $article->filename .
        '.xml';
$xsl = dm::getDir() . '/dmCorePlugin/plugins/sidWidgetBePlugin/lib/xsl/' . sfConfig::get('app_xsl-article');
$dataType = getDataTypeXml($xml);

// on affiche un message seulement pour l'environnement de dev
if (sfConfig::get('sf_environment') == 'dev') {
    echo _tag(
            'div.debug', array('onClick' => '$(this).hide();'), _tag('span.type', 'id LEA:') . ' ' . _tag('span.value', $article->filename) . '  ' .
            _tag('span.type', 'DataType:') . ' ' . _tag('span.value', $dataType)
    );
}

// traitement des dossiers
if ($dataType == 'DOSSIER')
    echo writeDossier($xml, $xsl, $article);
elseif ($dataType == 'ARTICLE')
    echo writeArticle($xml, $xsl, $article);
else {
    // ni DOSSIER ni ARTICLE 
}


/*
 * Retourne la valeur de l'étiquette dataType du xml
 */

function getDataTypeXml($xml) {
    $return = '';
    // vérification des fichiers xml
    if (!is_file($xml)) {

        if (sfConfig::get('sf_environment') == 'dev') {
            $return = _tag(
                    'div.debug', array('onClick' => '$(this).hide();'), __('Error : missed file') . '  ' . $xml
            );
        }
    }
// Je charge en mémoire mon document XML
    $doc_xml = new DOMDocument();

// recherche typeXML dossier / article
    if ($doc_xml->load($xml)) {
        $return = (isset($doc_xml->getElementsByTagName("DataType")->item(0)->nodeValue)) ? $doc_xml->getElementsByTagName("DataType")->item(0)->nodeValue : "";
    } else {
        $return = 'ERREUR : XML invalide :' . $xml;
    }
    return $return;
}

/*
 * Retourne un article xml formaté par le XSL, en html
 */

function writeArticle($xml, $xsl, $article, $articleIntoDossier = false) {
    $return = '';

    $section = $article->getSectionPageName();
    $rubrique = $article->getRubriquePageName();

    // vérification des fichiers xml
    if (!is_file($xml)) {

        if (sfConfig::get('sf_environment') == 'dev') {
            $return .= _tag(
                    'div.debug', array('onClick' => '$(this).hide();'), __('Error : missed file') . '  ' . $xml
            );
        }
    }

    $doc_xml = new DOMDocument();
    if ($doc_xml->load($xml)) {
        // Je charge en mï¿½moire mon document XSL
        // vérification des fichiers xsl
        if (!is_file($xsl)) {

            if (sfConfig::get('sf_environment') == 'dev') {
                $return .= _tag(
                        'div.debug', array('onClick' => '$(this).hide();'), __('Error : missed file') . '  ' . $xsl
                );
            }
        }
        $doc_xsl = new DOMDocument();
        $doc_xsl->load($xsl);

// Configuration du transformateur xsl
        $moteurXslt = new xsltProcessor();
        $moteurXslt->importstylesheet($doc_xsl);

// Transformation du document XML en XHTML et sauvegarde du résultat (Arnaud : j'ai remplacé transformtodoc par transformToXML)
        $output = $moteurXslt->transformToXML($doc_xml);

        $return .= writeHeadArticle($article, false, $articleIntoDossier); // false pour le second paramètre pour ne pas afficher le chapeau
        //Contenu de l'article
        $return .= $output;

//Fermeture de l'article
        $return .= _close('article');
    } else {
        $return = 'ERREUR : XML invalide :' . $xml;
    }
    return $return;
}

/*
 * Retourne un article xml formaté par le XSL, en html
 */

function writeHeadArticle($article, $afficheChapeau = false, $articleIntoDossier = false) {

    $section = $article->getSectionPageName();
    $rubrique = $article->getRubriquePageName();

    $return = '';
    if (!$articleIntoDossier) {
        $return .= _tag('h2.title', $rubrique . ' - ' . $section);
    }
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

    if ($articleIntoDossier) {
        $return .= _tag('h3.title itemprop="name"', $article->title);
    } else {
        $return .= _tag('h2.title itemprop="name"', $article->title);
    }
    if ($afficheChapeau) {
        $return .= _tag('span.teaser itemprop="description"', $article->chapeau);
    }

    return $return;
}

/*
 * Retourne un article xml formaté par le XSL, en html
 */

function writeDossier($xml, $xsl, $article) {

    $section = $article->getSectionPageName();
    $rubrique = $article->getRubriquePageName();

    $return = writeHeadArticle($article, true); // pour le chapeau de l'article de dataType DOSSIER

    $doc_xml = new DOMDocument();
    if ($doc_xml->load($xml)) {
        $sections = $doc_xml->getElementsByTagName("Section");
        $linkedArticles = array();

        foreach ($sections as $section) {

            $AssociatedWiths = $section->getElementsByTagName("AssociatedWith");
            foreach ($AssociatedWiths as $AssociatedWith) {
                $linkedArticles[] = (isset($AssociatedWith->getElementsByTagName("Reference")->item(0)->nodeValue)) ? $AssociatedWith->getElementsByTagName("Reference")->item(0)->nodeValue : "";
            }
        }
    } else {
        $return .= 'ERREUR : XML invalide :' . $xml;
    }

    foreach ($linkedArticles as $linkedArticle) {
        $linkedSidArticle = Doctrine_Core::getTable('SidArticle')->findOneByFilename($linkedArticle);
        $return .= writeArticle($xml, $xsl, $linkedSidArticle, true);
    }

    return $return;
}