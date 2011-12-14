<?php

$section = $article->getSectionPageName();
$rubrique = $article->getRubriquePageName();


$return = '';

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

$return .= _tag('h3.title itemprop="name"', $article->title);


$return .= _tag('span.teaser itemprop="description"', $article->chapeau);


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
    include_partial('article/showArticleInDossier', array('xml' => $xml, 'xsl' => $xsl, 'article' => $article));
}

echo $return;
?>
