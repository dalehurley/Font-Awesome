<?php
// Vars: $article
// Vars: $articleTags

$xmlFile = sfConfig::get('app_rep-local') .
        $article->getSection()->getRubrique() .
        '/' .
        $article->getSection() .
        '/' .
        $article->filename .
        '.xml';

$xslFile = dm::getDir().'/dmCorePlugin/plugins/sidWidgetBePlugin/lib/xsl/'.sfConfig::get('app_xsl-article');

// on affiche un message que pour l'environnement de dev
if (sfConfig::get('sf_environment') == 'dev') {
    echo _tag(
            'div.debug', array('onClick' => '$(this).hide();'), _tag('span.type', 'id LEA&#160;:').' '._tag('span.value', $article->filename)
    );
}

// Adresse de mon document DocBook XML
$domxml = $xmlFile;

// Adresse du docbook XSL
$domxsl = $xslFile;

if (!is_file($domxml) || !is_file($domxsl)) {

    if (sfConfig::get('sf_environment') == 'dev') {
        echo _tag(
                'div.debug', array('onClick' => '$(this).hide();'), __('Error : missed file')
        );
    }
}

// Je charge en mï¿½moire mon document DocBook XML
$doc_xml = new DOMDocument();
$doc_xml->load($domxml);

// Je charge en mï¿½moire mon document DocBook XSL
$doc_xsl = new DOMDocument();
$doc_xsl->load($domxsl);

// Configuration du transformateur
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
echo '<article itemscope itemtype="http://schema.org/Article">';

	echo _tag('h2.title itemprop="name"', $article->title);

	//lien vers l'image
	$imgLink = '/_images/lea' . $article->filename . '-g.jpg';
	//on vérifie que l'image existe
	$imgExist = is_file(sfConfig::get('sf_web_dir') . $imgLink);
	
	// on teste si le fichier image est présent sur le serveur avec son chemin absolu
	if ($imgExist) {
		echo _open('div.imageFullWrapper');
			echo _media($imgLink)
						->set('.image itemprop="image"')
						->alt($article->getTitle())
						//redimenssionnement propre lorsque l'image sera en bibliothèque
						->width(spLessCss::gridGetContentWidth());
						//->height(spLessCss::gridGetHeight(14,0))
		echo _close('div');
	}
	
	/*
	echo _open('section.contentTop');
		//
	echo _close('section');
	 */
	
	//Contenu de l'article
	echo $output;

//Fermeture de l'article
echo _close('article');