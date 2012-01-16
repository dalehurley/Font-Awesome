<?php
/*
 * _publicationListElement.php
 * v0.1
 * Permet d'afficher une publication de façon simplifié à l'intérieur d'une liste ul li
 * 
 * Variables disponibles :
 * $node		élément englobant tous les objet de la page
 * $itemType	indique le type de node 'Person', 'Article', etc
 * $linkUrl		indique si il s'agit d'un lien ou non
 * $title
 * $image
 * $teaser
 * $rubrique	indique la rubrique (utilisé essentiellement par l'équipe
 * 
 */
$html = '';
//Définitions des valeurs par défaut
$isImage = false;
if(isset($image)) {
	//on vérifie que l'image existe sur le serveur avec son chemin absolu
	$imageUpload = (strpos($image, 'uploads') === false) ? '/uploads/' : '/';
	$isImage = is_file(sfConfig::get('sf_web_dir') . $imageUpload . $image);
}



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

	//ouverture du lien si nécessaire
	if(isset($linkUrl)) $html.= _open('a.link.link_box', array('href' => $linkUrl, 'title' => $title));

		//création de l'image seulement si présente
		if($isImage){
			//calcul des dimensions de l'image
			$imgColWidth = ($itemType == 'Person') ? spLessCss::getLessParam('thumbM_col') : spLessCss::getLessParam('thumbL_col');
			$imgColHeight = ($itemType == 'Person') ? spLessCss::getLessParam('thumbX_bl') : spLessCss::getLessParam('thumbL_bl');

			$html.= get_partial('global/imageWrapper', array(
														'image'	=>	$image,
														'alt'	=>	$title,
														'width'	=>	spLessCss::gridGetWidth($imgColWidth,0),
														'height'=>	spLessCss::gridGetHeight($imgColHeight,0)
														));
		}

		//si une image est présente on englobe le texte dans un wrapper pour faciliter son placement CSS par rapport à cette dernière
		if($isImage) $html.= _open('span.wrapper');
			
			//créer des partials pour les différents sous-éléments
			if($itemType == 'Person'){
				$html.= _open('div.subWrapper');
					$html.= _tag('span.name itemprop="name"', $node->getTitle());
					$html.= '&#160;-&#160;';
					$html.= _tag('span.jobTitle itemprop="jobTitle"', $node->getStatut());
				$html.= _close('div');
				
				$html.= _open('span.telephone');
					$html.= _tag('span.type', __('phone'));
					$html.= '&#160;';
					$html.= _tag('span.value itemprop="telephone"', $node->getTel());
				$html.= _close('span');
				
				$html.= _tag('div', array(), 'test : ' . sfConfig::get('sf_web_dir') . $image);
				
				if(isset($rubrique)) {
					if($rubrique != null) {
						$html.= _open('span.rubrique');
							$html.= _tag('span.type', __('Responsable in'));
							$html.= '&nbsp;:&nbsp;';
							$html.= _tag('span.value', $rubrique);
						$html.= _close('span');
					}
				}
				
				$html.= _open('span.email');
					$html.=  _tag('span.type', __('Email'));
					$html.=  '&nbsp;:&nbsp;';
					$html.=  _tag('span.value', _link('mailto:' . $node->getEmail())->text($node->getEmail())->set('itemprop="email"'));
				$html.=  _close('span');
				
				$html.= get_partial('global/descriptionWrapper', array('teaser' => $node->getText()));
			}else{
				if(isset($title))										$html.= get_partial('global/titleWrapper', array('title' => $title));
				if(isset($node->created_at) && $itemType == 'Article')	$html.= get_partial('global/dateWrapperShort', array('node' => $node));
				if(isset($teaser))										$html.= get_partial('global/teaserWrapper', array('teaser' => $teaser));
			}
			
		if($isImage) $html.= _close('span.wrapper');

	//fermeture du lien si nécessaire
	if(isset($linkUrl)) $html.= _close('a.link.link_box');
	
//fermeture container de publication
$html.= _close('li.element');

//affichage html en sortie
echo $html;