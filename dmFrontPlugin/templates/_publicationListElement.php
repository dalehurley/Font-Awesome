<?php
/*
 * _publicationListElement.php
 * v0.6
 * Permet d'afficher une publication de façon simplifié à l'intérieur d'une liste ul li
 * 
 * Variables disponibles :
 * $node		élément englobant tous les objet de la page
 * $itemType	indique le type de node 'Person', 'Article', 'Organization' etc
 * $linkUrl		indique si il s'agit d'un lien ou non
 * $title
 * $image
 * $teaser
 * $teaserLength
 * $navigationElements
 * $rubrique	indique la rubrique (utilisé essentiellement par l'équipe
 * $count		indique le numéro de listing
 * $maxCount	indique le nombre maximal d'éléments affichages
 * $isLight		permet d'indiquer une version allégée (notamment pour des affichages spéciaux dans les sidebars)
 * 
 */

//Déclaration des classes du container contenant l'article
$ctnOpts = array();
$ctnClass = array('element');

//Définition du type de contenu de la publication
if(isset($itemType)) {
	//vérification de différent cas de valeur pour itemType
	switch ($itemType) {
		case 'Article':		$isSchema = true;	break;
		case 'Person':		$isSchema = true;	break;
		case 'Organization':$isSchema = true;	break;
		default:			$isSchema = false;	break;
	}
	
	if($isSchema) {
		$ctnOpts['itemscope'] = 'itemscope';
		$ctnOpts['itemtype'] = 'http://schema.org/' . $itemType;
	}
	
	//ajout de itemType aux classes
	$ctnClass[] = $itemType;
}
else $itemType = null;

//gestion de l'index de positionnement
if(isset($count) && isset($maxCount)) {
	if($count == 1)			$ctnClass[] = 'first';
	if($count >= $maxCount)	$ctnClass[] = 'last';
}

//récupérations des options de page et application classe de debug
$pageOptions = spLessCss::pageTemplateGetOptions();
$isDev = $pageOptions['isDev'];
if($isDev) $ctnClass[] = 'isVerified';

//Ajout des classes dans les options du container
$ctnOpts = array('class' => $ctnClass);

//Définitions des valeurs par défaut

//permet de ne pas être obligé de définir cette variable lorsque égale à false
if(!isset($isLight)) $isLight = false;

//on affecte les valeurs par défaut en fonction de la node passée en paramètre
if(isset($node)) {
	//si les valeurs ne sont pas explicitement définies on récupère la valeur dans la node
	if(!isset($title)) {
		try { $title = $node->getTitle(); }
		catch(Exception $e) { $title = null; }
	}
	if(!isset($image)) {
		try { $image = $node->getImage(); }
		catch(Exception $e) { $image = null; }
	}
	if(!isset($teaser)) {
		try { $teaser = $node->getResume(); }
		catch(Exception $e) { $teaser = null; }
	}
}

//définition de l'image
$isImage = false;
if(isset($image)) {
	//on vérifie que l'image existe sur le serveur avec son chemin absolu
	$imageUpload = (strpos($image, 'uploads') === false) ? '/uploads/' : '/';
	$isImage = is_file(sfConfig::get('sf_web_dir') . $imageUpload . $image);
}


//ouverture container de publication (ajout classe de dev)
$html = _open('li', $ctnOpts);
	
	//variable de remplissage du li
	$htmlLi = '';
	
	//création de l'image seulement si présente
	if($isImage){
		//calcul des dimensions de l'image
		if($itemType == 'Person') {
			$imgColWidth = ($isLight) ? spLessCss::getLessParam('thumbS_col') : spLessCss::getLessParam('thumbM_col');
			$imgColHeight = ($isLight) ? spLessCss::getLessParam('thumbS_bl') : spLessCss::getLessParam('thumbM_bl') * 2;
		}else{
			$imgColWidth = ($isLight) ? spLessCss::getLessParam('thumbM_col') : spLessCss::getLessParam('thumbL_col');
			$imgColHeight = ($isLight) ? spLessCss::getLessParam('thumbM_bl') * 2 : spLessCss::getLessParam('thumbL_bl');
		}
		
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
		if($itemType == 'Organization') {
			
			$htmlLi.= "Test d'adresse";
				
			$html.= get_partial('global/schema/Thing/Organization', array('node' => $node));
			$html.= '<br/>';
			$html.= '<br/>';
			
			/*
			$htmlLi.= _tag('span.name itemprop="name"', $node->getTitle());
			
			//affichage de l'adresse (passer en partial)
			$htmlLi.= _open('div.subWrapper.PostalAddress itemprop="address" itemscope itemtype="http://schema.org/PostalAddress');
				
				
			$htmlLi.= _close('div');
			 * 
			 */
		}
		elseif($itemType == 'Person'){
			$htmlLi.= _open('div.subWrapper');
				$htmlLi.= _tag('span.name itemprop="name"', $node->getTitle());
				$htmlLi.= '&#160;-&#160;';
				$htmlLi.= _tag('span.jobTitle itemprop="jobTitle"', $node->getStatut());
			$htmlLi.= _close('div');
			
			$htmlLi.= get_partial('global/schemaTypeValue', array(
																'itemType'	=> 'telephone',
																'type'		=> __('Phone'),
																'value'		=> $node->getTel()
															));
			
			//affichage de la rubrique si définit et option isLight désactivée
			if(isset($rubrique) && !$isLight) $htmlLi.= get_partial('global/schemaTypeValue', array(
																'itemType'	=> 'rubrique',
																'type'		=> __('Responsable in'),
																'value'		=> $rubrique,
																'noProp'	=> true
																));
			
			//options d'affichage de l'email
			$emailOpt = array(
							'itemType'	=> 'email',
							'type'		=> __('Email'),
							'value'		=> $node->getEmail()
						);
			//si l'URL n'est pas définit pour le listing alors on peut rajouter l'email dans l'affichage
			if(!isset($linkUrl)) $emailOpt['linkUrl'] = 'mailto:' . $node->getEmail();
			$htmlLi.= get_partial('global/schemaTypeValue', $emailOpt);
			
			//affichage de la description
			if(!$isLight) $htmlLi.= get_partial('global/descriptionWrapper', array('teaser' => $node->getText()));
			
		}else{
			if(isset($title))										$htmlLi.= get_partial('global/titleWrapper', array('title' => $title));
			if(isset($node->created_at) && $itemType == 'Article')	$htmlLi.= get_partial('global/dateWrapperShort', array('node' => $node));
			if(isset($teaser)) {
				$teaserOpts = array('teaser' => $teaser);
				if(isset($teaserLength)) $teaserOpts['length'] = $teaserLength;
				$htmlLi.= get_partial('global/teaserWrapper', $teaserOpts);
			}
		}

	if($isImage) $htmlLi.= _close('span.wrapper');
	
	//inclusion dans le lien si nécessaire
	if(isset($linkUrl)) {
		$html.= _link($linkUrl)->text($htmlLi)->title($title)->set('.link_box');
	}else{
		$html.= $htmlLi;
	}
	
	//ajout de liens de navigation si nécessaire
	if(isset($navigationElements)) {
		$html.= get_partial('global/navigationWrapper', array(
														'placement' => 'bottom',
														'elements' => $navigationElements,
														'isLight' => true
														));
	}
	
//fermeture container de publication
$html.= _close('li');

//affichage html en sortie
echo $html;