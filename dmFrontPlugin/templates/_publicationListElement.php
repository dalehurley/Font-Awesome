<?php
/*
 * _publicationListElement.php
 * v0.1
 * Permet d'afficher une publication de façon simplifié à l'intérieur d'une liste ul li
 * 
 * Variables disponibles :
 * $node		élément englobant tous les objet de la page
 * $itemType	indique le type de node
 * $linkUrl		indique si il s'agit d'un lien ou non
 * $category
 * $section
 * $title
 * $image
 * $teaser
 * 
 */

//Définitions des valeurs par défaut

//on vérifie que l'image existe sur le serveur avec son chemin absolu
$isImage = isset($image) ? is_file(sfConfig::get('sf_web_dir') . $image) : false;


$html = '';

//Déclaration des options du container contenant l'article
switch ($itemType) {
	case 'Article':
		$ctnOpts['id'] = 'article_' . $node->id;
		$ctnOpts['itemscope'] = 'itemscope';
		$ctnOpts['itemtype'] = 'http://schema.org/Article';
		break;
	case 'Person' :
		$ctnOpts['id'] = 'equipe_' . $node->id;
		$ctnOpts['itemscope'] = 'itemscope';
		$ctnOpts['itemtype'] = 'http://schema.org/Person';
	default:
		$ctnOpts['id'] = 'node_' . $node->id;
		break;
}

//ouverture container de publication
$html.= _open('li.element', $ctnOpts);


	//Pour les personnes
	if($itemType == 'Person') {
		
		
	} else {
		//ouverture du lien si nécessaire
		if(isset($linkUrl)) $html.= _open('a.link.link_box', array('href' => $linkUrl, 'title' => $title));

			//création de l'image seulement si présente
			if($isImage){
				$html.= get_partial('global/imageWrapper', array(
															'image'	=>	$image,
															'alt'	=>	$title,
															'width'	=>	spLessCss::gridGetWidth(spLessCss::getLessParam('thumbL_col'),0),
															'height'=>	spLessCss::gridGetHeight(spLessCss::getLessParam('thumbL_bl'),0)
															));
			}

			//si une image est présente on englobe le texte dans un wrapper pour faciliter son placement CSS par rapport à cette dernière
			if($isImage) $html.= _open('span.wrapper');

				if(isset($title))										$html.= get_partial('global/titleWrapper', array('title' => $title));
				if(isset($node->created_at) && $itemType == 'Article')	$html.= get_partial('global/dateWrapperShort', array('node' => $node));
				if(isset($teaser))										$html.= get_partial('global/teaserWrapper', array('teaser' => $teaser));

			if($isImage) $html.= _close('span.wrapper');

		//fermeture du lien si nécessaire
		if(isset($linkUrl)) $html.= _close('a.link.link_box');
	}
	
	
	
//fermeture container de publication
$html.= _close('li.element');

//affichage html en sortie
echo $html;