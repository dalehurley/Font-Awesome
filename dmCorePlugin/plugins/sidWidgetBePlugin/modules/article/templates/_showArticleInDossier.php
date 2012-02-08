<?php
/**
 * Retourne un sous-article de dossier xml formaté par le XSL, en html
 */

//html de sortie
$html = '';

//récupération de la section et de la rubrique
$section = $article->getSectionPagetitle();
$rubrique = $article->getRubriquePageTitle();

//ciblage XML et XSL
$xml = sfConfig::get('app_rep-local') .
        $article->getSection()->getRubrique() .
        '/' .
        $article->getSection() .
        '/' .
        $article->filename .
        '.xml';
$xsl = dm::getDir() . '/dmCorePlugin/plugins/sidWidgetBePlugin/lib/xsl/' . sfConfig::get('app_xsl-article');

// vérification du fichier XSL
if (!is_file($xsl)) $html.= debugTools::infoDebug(array(__('Error : missed file') => $xsl),'warning');

// vérification du fichier XML
if (!is_file($xml)) {
	$html.= debugTools::infoDebug(array(__('Error : missed file') => $xml),'warning');
} else {
	//titre du contenu
	$html.= get_partial('global/titleSupWrapper', array('title' => $article->title));
	
	//création du parser XML
	$doc_xml = new DOMDocument();
	
	//ouverture du document XML
	if ($doc_xml->load($xml)) {
		
		//récupération du contenu du XML
		$doc_xsl = new DOMDocument();
        $doc_xsl->load($xsl);
        $moteurXslt = new xsltProcessor();
        $moteurXslt->importstylesheet($doc_xsl);
		//on envoie un parametre permettant d'afficher l'image
        $moteurXslt->setParameter('', 'imageAffiche', 'true');
		
		//ajout du contenu à afficher
		$html.= $moteurXslt->transformToXML($doc_xml);
		
    } else {
		$html.= debugTools::infoDebug(array(__('Error : invalid xml') => $xml),'warning');
    }
}

//déclaration des propriétés par défaut du container
$wrapperOpt = array('id' => 'supWrapper_' . $article->id);

//gestion de l'index de positionnement
if(isset($count) && isset($maxCount)) {
	if($count == 1)			$wrapperOpt['class'][] = 'first';
	if($count >= $maxCount)	$wrapperOpt['class'][] = 'last';
}

//inclusion du contenu dans un supWrapper
$html = _tag('section.supWrapper', $wrapperOpt, $html);

//affichage html en sortie
echo $html;