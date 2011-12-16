<?php

// Vars: $article


$xml = sfConfig::get('app_rep-local') .
        $article->getSection()->getRubrique() .
        '/' .
        $article->getSection() .
        '/' .
        $article->filename .
        '.xml';
$xsl = dm::getDir() . '/dmCorePlugin/plugins/sidWidgetBePlugin/lib/xsl/' . sfConfig::get('app_xsl-article');

if (!is_file($xml)) {
    echo debugTools::infoDebug(array(__('Error : missed file') => $xml), 'warning');
}

$dataType = xmlTools::getLabelXml($xml, "DataType");
echo debugTools::infoDebug(array('id LEA' => $article->filename, 'DataType' => $dataType));

// traitement des dossiers
if ($dataType == 'DOSSIER')
    include_partial('article/showDossier', array('article' => $article));
elseif ($dataType == 'ARTICLE')
    include_partial('article/showArticle', array('article' => $article));
else {
    // ni DOSSIER ni ARTICLE 
}



