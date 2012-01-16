<?php
/*
 * _publicationListElement.php
 * v0.3
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
 * $count		indique le numéro de listing
 * $maxCount	indique le nombre maximal d'éléments affichages
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

//gestion de l'index de positionnement
$posClass = '';
if(isset($count) && isset($maxCount)) {
	if($count == 1)				$posClass = '.first';
	elseif($count >= $maxCount)	$posClass = '.last';
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
		break;
	default:
		$ctnOpts['id'] = 'node_' . $node->id;
		break;
}

//ouverture container de publication
$html.= _open('li.element' . $posClass, $ctnOpts);
	
	//variable de remplissage du li
	$htmlLi = '';
	
	//création de l'image seulement si présente
	if($isImage){
		//calcul des dimensions de l'image
		$imgColWidth = ($itemType == 'Person') ? spLessCss::getLessParam('thumbM_col') : spLessCss::getLessParam('thumbL_col');
		$imgColHeight = ($itemType == 'Person') ? spLessCss::getLessParam('thumbM_bl') * 2 : spLessCss::getLessParam('thumbL_bl');

		$htmlLi.= get_partial('global/imageWrapper', array(
													'image'	=>	$image,
													'alt'	=>	$title,
													'width'	=>	spLessCss::gridGetWidth($imgColWidth,0),
													'height'=>	spLessCss::gridGetHeight($imgColHeight,0)
													));
	}

	//si une image est présente on englobe le texte dans un wrapper pour faciliter son placement CSS par rapport à cette dernière
	if($isImage) $htmlLi.= _open('span.wrapper');

		//créer des partials pour les différents sous-éléments
		if($itemType == 'Person'){
			$htmlLi.= _open('div.subWrapper');
				$htmlLi.= _tag('span.name itemprop="name"', $node->getTitle());
				$htmlLi.= '&#160;-&#160;';
				$htmlLi.= _tag('span.jobTitle itemprop="jobTitle"', $node->getStatut());
			$htmlLi.= _close('div');

			$htmlLi.= _open('span.telephone');
				$htmlLi.= _tag('span.type', __('phone'));
				$htmlLi.= '&#160;';
				$htmlLi.= _tag('span.value itemprop="telephone"', $node->getTel());
			$htmlLi.= _close('span');

			if(isset($rubrique)) {
				if($rubrique != null) {
					$htmlLi.= _open('span.rubrique');
						$htmlLi.= _tag('span.type', __('Responsable in'));
						$htmlLi.= '&nbsp;:&nbsp;';
						$htmlLi.= _tag('span.value', $rubrique);
					$htmlLi.= _close('span');
				}
			}

			$htmlLi.= _open('span.email');
				$htmlLi.=  _tag('span.type', __('Email'));
				$htmlLi.=  '&nbsp;:&nbsp;';		
				if(isset($linkUrl)){
					$htmlLi.= _tag('span.value itemprop="email"', $node->getEmail());
				}else{
					$htmlLi.= _tag('span.value', _link('mailto:' . $node->getEmail())->text($node->getEmail())->set('.link itemprop="email"'));
				}
				
			$htmlLi.=  _close('span');

			$htmlLi.= get_partial('global/descriptionWrapper', array('teaser' => $node->getText()));
		}else{
			if(isset($title))										$htmlLi.= get_partial('global/titleWrapper', array('title' => $title));
			if(isset($node->created_at) && $itemType == 'Article')	$htmlLi.= get_partial('global/dateWrapperShort', array('node' => $node));
			if(isset($teaser))										$htmlLi.= get_partial('global/teaserWrapper', array('teaser' => $teaser));
		}

	if($isImage) $htmlLi.= _close('span.wrapper');
	
	//inclusion dans le lien si nécessaire
	if(isset($linkUrl)) {
		$html.= _link($linkUrl)->text($htmlLi)->title($title)->set(isset($image) ? '.link_box' : '');
	}else{
		$html.= $htmlLi;
	}
	
//fermeture container de publication
$html.= _close('li.element');

//affichage html en sortie
echo $html;