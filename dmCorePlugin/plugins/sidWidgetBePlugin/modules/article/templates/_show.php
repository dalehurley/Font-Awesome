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

// on affiche un message que pour l'environnement de dev
if (sfConfig::get('sf_environment') == 'dev') {
    echo _tag(
            'div.debug', array('onClick' => '$(this).hide();'), _tag('span.type', 'id LEA&#160;:') . ' ' . _tag('span.value', $article->filename)
    );
}

// vérification des fichiers xml
if (!is_file($xml)) {

    if (sfConfig::get('sf_environment') == 'dev') {
        echo _tag(
                'div.debug', array('onClick' => '$(this).hide();'), __('Error : missed file') . '  ' . $xml
        );
    }
}

// Je charge en mémoire mon document XML
$doc_xml = new DOMDocument();

// recherche typeXML dossier / article
if ($doc_xml->load($xml)) {
    $dataType = (isset($doc_xml->getElementsByTagName("DataType")->item(0)->nodeValue)) ? $doc_xml->getElementsByTagName("DataType")->item(0)->nodeValue : "";
} else {
    $return[$j]['ERREUR : XML invalide ' . $xmlFile] = $xmlFile . '.xml Invalide';
}

echo ">>>" . $dataType;

// traitement des dossiers
if ($dataType == 'DOSSIER') {
    echo writeDossier($doc_xml, $rubrique, $section, $article);
} elseif ($dataType == 'ARTICLE') {
    echo writeArticle($doc_xml, $rubrique, $section, $article);
} else {
    // ni DOSSIER ni ARTICLE
}


/*
 * Retourne un article xml formaté par le XSL, en html
 */

function writeArticle($doc_xml, $rubrique, $section, $article) {
    $return = '';
    // Je charge en mï¿½moire mon document DocBook XSL
    $xsl = dm::getDir() . '/dmCorePlugin/plugins/sidWidgetBePlugin/lib/xsl/' . sfConfig::get('app_xsl-article');
    // vérification des fichiers xml
    if (!is_file($xsl)) {

        if (sfConfig::get('sf_environment') == 'dev') {
            echo _tag(
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
    $obj = $moteurXslt->transformToXML($doc_xml);

//$output = $obj->savexml();
    $output = $obj;
//echo _tag('h2.title', $nomSection);
//Modifications ARNAUD : structure sÃ©mantique d'un article
//_tag ne supporte par les attributs sans valeur
//echo _open('div.article', array('itemscope' => '', 'itemtype' => 'http://schema.org/Article'));

$return = writeHeadArticle($rubrique, $section, $article);

    /*
      echo _open('section.contentTop');
      //
      echo _close('section');
     */

    //Contenu de l'article
    $return .= $output;

//Fermeture de l'article
    $return .= _close('article');

    return $return;
}


/*
 * Retourne un article xml formaté par le XSL, en html
 */

function writeHeadArticle($rubrique, $section, $article, $afficheChapeau = false) {

    $return = '';
    $return = _tag('h2.title', $rubrique . ' - ' . $section);
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
    
    if ($afficheChapeau)  $return .= _tag('span.teaser itemprop="description"', $article->chapeau);
    
    return $return;
    }


/*
 * Retourne un article xml formaté par le XSL, en html
 */

function writeDossier($doc_xml, $rubrique, $section, $article) {

//    $filename = $doc_xml->getElementsByTagName('Code')->item(0)->nodeValue; // l'id LEA est aussi le nom du fichier XML
//    $titre = $doc_xml->getElementsByTagName('Headline')->item(0)->nodeValue;  //titre
//    $chapo = $doc_xml->getElementsByTagName('Head')->item(0)->nodeValue; // chapo
    
    $return = writeHeadArticle($rubrique, $section, $article, true);
    
//    $articles = $xml->getElementsByTagName("article");
//    $dessins = array();
//
//    foreach ($articles as $article) {
//        $idArticle = $article->getAttribute("idArticle");
//
//        $dessins[$idArticle]['rubrique'] = $article->getAttribute("rubrique");
//        $dessins[$idArticle]['auteurArticle'] = $article->getAttribute("auteurArticle");
//        $dessins[$idArticle]['dateArticle'] = $article->getAttribute("dateArticle");
//        $dessins[$idArticle]['site'] = $article->getAttribute("site");
//        // on considère le premier objet des tags suivants, contenu dans l'article, donc ->item(0)
//        $dessins[$idArticle]['copyright'] = (isset($article->getElementsByTagName("copyright")->item(0)->nodeValue)) ? $article->getElementsByTagName("copyright")->item(0)->nodeValue : "";
//        $dessins[$idArticle]['titre'] = (isset($article->getElementsByTagName("titre")->item(0)->nodeValue)) ? $article->getElementsByTagName("titre")->item(0)->nodeValue : "";
//        $dessins[$idArticle]['chapeau'] = (isset($article->getElementsByTagName("chapeau")->item(0)->nodeValue)) ? $article->getElementsByTagName("chapeau")->item(0)->nodeValue : "";
//        $dessins[$idArticle]['contenu'] = (isset($article->getElementsByTagName("contenu")->item(0)->nodeValue)) ? $article->getElementsByTagName("contenu")->item(0)->nodeValue : "";
//
//        //lien vers l'image du dessin
//        $dessins[$idArticle]['imgLinkBig'] = '/_images/' . $idArticle . '-b.jpg';
//        $dessins[$idArticle]['imgLinkSmall'] = '/_images/' . $idArticle . '-a.jpg';
//    }
    
    
    return $return;
}