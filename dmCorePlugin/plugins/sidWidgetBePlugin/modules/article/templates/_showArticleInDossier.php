<?php

/**
 * Retourne un sous-article de dossier xml formaté par le XSL, en html
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
$section = $article->getSectionPagetitle();
$rubrique = $article->getRubriquePageTitle();

// vérification des fichiers xml
if (!is_file($xml)) {
<<<<<<< HEAD
    $return .= debugTools::infoDebug(array(__('Error : missed file').'1'=>$xml),'warning');
}
=======
    $return .= debugTools::infoDebug(array(__('Error : missed file') => $xml), 'warning');
} else {
>>>>>>> f4e90b3ac7c9d249eb802d89b48143addaca31b0

    $doc_xml = new DOMDocument();
    if ($doc_xml->load($xml)) {
        if (!is_file($xsl)) {
            $return .= debugTools::infoDebug(array(__('Error : missed file') => $xsl), 'warning');
        }
        //lien vers l'image
        $imgLink = '/_images/lea' . $article->filename . '-g.jpg';
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

        $return .= _tag('h4.title itemprop="name"', $article->title);

// Transformation du document XML en XHTML et sauvegarde du résultat 
        $doc_xsl = new DOMDocument();
        $doc_xsl->load($xsl);
        $moteurXslt = new xsltProcessor();
        $moteurXslt->importstylesheet($doc_xsl);
        //  on envoie un parametre permettant d'afficher l'image
        $moteurXslt->setParameter('', 'imageAffiche', 'true');

        $output = $moteurXslt->transformToXML($doc_xml);
        $return .= $output;
    } else {
        $return .= debugTools::infoDebug(array(__('Error : invalid file') => $xml), 'warning');
    }
}
echo $return;
?>
