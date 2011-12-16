<?php

/*
 * Retourne un article xml formaté par le XSL, en html
 */

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




   // $return .= _tag('h2.title', $rubrique . ' - ' . $section);

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


    //$return .= _tag('span.teaser itemprop="description"', $article->chapeau);



    //Contenu de l'article
    $return .= $output;

//Fermeture de l'article
    $return .= _close('article');
} else {
    $return = 'ERREUR : XML invalide :' . $xml;
}

echo $return;
?>
