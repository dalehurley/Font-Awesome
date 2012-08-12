<?php

// Vars: $article

if (!is_file($xml)) {
    echo debugTools::infoDebug(array(__('Error : missed file') => $xml), 'error');
}

echo debugTools::infoDebug(array('id LEA' => $article->filename, 'DataType' => $dataType));

// traitement des dossiers
if ($dataType == 'DOSSIER'){
    include_partial('article/showDossier', 
        array(
            'article' => $article,
            'withImage' => $withImage,
            //'widthImage' => $widthImage
            ));
    $pageSession = sfContext::getInstance()->getUser()->setAttribute('articleDataType', sfConfig::get('app_article-data-type-dossier'));
}
elseif (($dataType == 'ARTICLE')){
    include_partial('article/showArticle', 
        array(
            'article' => $article,
            'withImage' => $withImage,
            'widthImage' => $widthImage
            ));
    $pageSession = sfContext::getInstance()->getUser()->setAttribute('articleDataType', sfConfig::get('app_article-data-type-article'));
}
elseif ($dataType == 'AGENDA'){ // on affiche l'article de dataType AGENDA suivi de tous les autres articles du mois
    include_partial('article/showArticle', 
        array(
            'article' => $article,
            'withImage' => $withImage,
            'widthImage' => $widthImage,
//            'articleList' => $articleList
            ));
    $pageSession = sfContext::getInstance()->getUser()->setAttribute('articleDataType', sfConfig::get('app_article-data-type-agenda'));
}
else {
    // ni DOSSIER ni ARTICLE 
}





