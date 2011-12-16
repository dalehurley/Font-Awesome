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

$dataType = xmlTools::getDataTypeXml($xml, "DataType");

// on affiche un message seulement pour l'environnement de dev
if (sfConfig::get('sf_environment') == 'dev') {
    echo _tag(
            'div.debug', array('onClick' => '$(this).hide();'), _tag('span.type', 'id LEA:') . ' ' . _tag('span.value', $article->filename) . '  ' .
            _tag('span.type', 'DataType:') . ' ' . _tag('span.value', $dataType)
    );
}

// traitement des dossiers
if ($dataType == 'DOSSIER')
    include_partial('article/showDossier', array('xml' => $xml, 'xsl' => $xsl, 'article' => $article));
elseif ($dataType == 'ARTICLE')
//echo writeArticle($xml, $xsl, $article);
    include_partial('article/showArticle', array('xml' => $xml, 'xsl' => $xsl, 'article' => $article));
else {
    // ni DOSSIER ni ARTICLE 
}



