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
	if($article->title) $html = '<h3 class="title">'.$article->title.'</h3>';
	
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
//        $moteurXslt->setParameter('', 'imageAffiche', 'true');
                
                //récupération de l'image au dossier affiché
        $nameImage = '';
                $multimediaImages = $doc_xml->getElementsByTagName('MultimediaInsert');
                foreach ($multimediaImages as $multimediaImage) {
                    $nameImage = '';
                    $nameImage = $multimediaImage->getElementsByTagName('FileName')->item(0)->nodeValue;
                    if (strpos($nameImage, '-p.jpg')){
                        break;
                    }
                }
                
                // vérification du nom de l'image en vérifiant avec le nom de l'image dans le xml et/ou avec le prefixe 'images' ???
                $imageExist = false;
                $imageLink = '/_images/' . $nameImage;
                if (is_file(sfConfig::get('sf_web_dir') . $imageLink)) {
                    $imageExist = true;
                } elseif (!is_file(sfConfig::get('sf_web_dir') . $imageLink)) {
                    $imageLink = '/_images/images' . $nameImage;
                    if (is_file(sfConfig::get('sf_web_dir') . $imageLink)) {
                        $imageExist = true;
                    }
                }
                else
                    $imageExist = false;
                
                
		$imageHtml = '';
		if ($imageExist){
			$imageHtml = 	'<div class="imageFullWrapper">'.
                                            '<img  src="'.$imageLink.'" itemprop="image" class="image" alt="'.$article->title.'">'.
                                        '</div>';
		}
                //ajout du contenu à afficher
		$html.= $imageHtml.$moteurXslt->transformToXML($doc_xml);
		
    } else {
		$html.= debugTools::infoDebug(array(__('Error : invalid xml') => $xml),'warning');
    }
}

//déclaration des propriétés par défaut du container
$wrapperOpt = array('id' => dmString::slugify($article.'-'.$article->id));

//gestion de l'index de positionnement
if(isset($count) && isset($maxCount)) {
	if($count == 1)			$wrapperOpt['class'][] = 'first';
	if($count >= $maxCount)	$wrapperOpt['class'][] = 'last';
}

//inclusion du contenu dans un supWrapper
$html = _tag('section.supWrapper', $wrapperOpt, $html);

//affichage html en sortie
echo $html;