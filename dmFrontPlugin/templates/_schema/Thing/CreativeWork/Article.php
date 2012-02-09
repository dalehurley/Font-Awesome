<?php
/*
 * Article.php
 * v1.2
 * http://schema.org/Article
 * 
 * Variables disponibles :
 * $node
 * $container
 * 
 * Properties from Thing :
 * $description
 * $image
 * $name
 * $url
 * 
 * Properties from CreativeWork :
 * about				Thing					The subject matter of the content.
 * accountablePerson 	Person					Specifies the Person that is legally accountable for the CreativeWork.
 * aggregateRating		AggregateRating			The overall rating, based on a collection of reviews or ratings, of the item.
 * alternativeHeadline 	Text					A secondary title of the CreativeWork.
 * associatedMedia		MediaObject				The media objects that encode this creative work. This property is a synonym for encodings.
 * audio				AudioObject				An embedded audio object.
 * author				Person or Organization 	The author of this content. Please note that author is special in that HTML 5 provides a special mechanism for indicating authorship via the rel tag. That is equivalent to this and may be used interchangabely.
 * awards				Text					Awards won by this person or for this creative work.
 * comment				UserComments			Comments, typically from users, on this CreativeWork.
 * contentLocation		Place					The location of the content.
 * contentRating		Text					Official rating of a piece of content—for example,'MPAA PG-13'.
 * contributor			Person or Organization 	A secondary contributor to the CreativeWork.
 * copyrightHolder		Person or Organization 	The party holding the legal copyright to the CreativeWork.
 * copyrightYear		Number					The year during which the claimed copyright for the CreativeWork was first asserted.
 * creator				Person or Organization 	The creator/author of this CreativeWork or UserComments. This is the same as the Author property for CreativeWork.
 * dateCreated			Date					The date on which the CreativeWork was created.
 * dateModified			Date					The date on which the CreativeWork was most recently modified.
 * datePublished		Date					Date of first broadcast/publication.
 * discussionUrl		URL						A link to the page containing the comments of the CreativeWork.
 * editor				Person					Specifies the Person who edited the CreativeWork.
 * encodings			MediaObject				The media objects that encode this creative work
 * genre				Text					Genre of the creative work
 * headline				Text					Headline of the article
 * inLanguage			Text					The language of the content. please use one of the language codes from the IETF BCP 47 standard.
 * interactionCount 	Text					A count of a specific user interactions with this item—for example, 20 UserLikes, 5 UserComments, or 300 UserDownloads. The user interaction type should be one of the sub types of UserInteraction.
 * isFamilyFriendly 	Boolean					Indicates whether this content is family friendly.
 * keywords				Text					The keywords/tags used to describe this content.
 * mentions				Thing					Indicates that the CreativeWork contains a reference to, but is not necessarily about a concept.
 * offers				Offer					An offer to sell this item—for example, an offer to sell a product, the DVD of a movie, or tickets to an event.
 * provider				Person or Organization 	Specifies the Person or Organization that distributed the CreativeWork.
 * publisher			Organization			The publisher of the creative work.
 * publishingPrinciples URL						Link to page describing the editorial principles of the organization primarily responsible for the creation of the CreativeWork.
 * reviews				Review					Review of the item.
 * sourceOrganization 	Organization			The Organization on whose behalf the creator was working.
 * thumbnailUrl			URL						A thumbnail image relevant to the Thing.
 * version				Number					The version of the CreativeWork embodied by a specified resource.
 * video				VideoObject				An embedded video object.
 * 
 * Properties from Article :
 * articleBody			Text					The actual body of the article.
 * articleSection		Text					Articles may belong to one or more 'sections' in a magazine or newspaper, such as Sports, Lifestyle, etc.
 * wordCount			Integer					The number of words in the text of the Article.
 * 
 */
//Définition du type (inséré dans le container si présent)
$itemType = "Article";

//récupération des valeurs dans la node par les getters par défaut
$includeDefault = sfConfig::get('dm_front_dir') . '/templates/_schema/_varsDefaultGetters.php';
include $includeDefault;

//Composition du html de sortie
$html = '';

//On organise le contenu différemment selon le type de version d'affichage
if($isListing) {
	//html image
	$htmlImage = '';
	 if((!isset($photo)) || ($photo == true)){
            //on affiche l'image que si elle est effectivement présente
            if($isImage && isset($image)){
                    //dimensions de l'image
                    $imageGridWidth = ($isLight) ? sidSPLessCss::getLessParam('thumbM_col') : sidSPLessCss::getLessParam('thumbL_col');
                    $imageGridHeight = ($isLight) ? sidSPLessCss::getLessParam('thumbM_bl') : sidSPLessCss::getLessParam('thumbL_bl');
                    //options de l'image
                    $imageWrapperOpts = array(
                                                                            'image'	=>	$image,
                                                                            'width'	=>	spLessCss::gridGetWidth($imageGridWidth,0),
                                                                            'height'=>	spLessCss::gridGetHeight($imageGridHeight,0)
                                                                            );
                    //ajout du nom de l'article dans la balise Alt de l'image
                    if(isset($name)) $imageWrapperOpts['alt'] = $name;

                    //Appel du partial d'image
                    $htmlImage.= get_partial('global/schema/DataType/Image', $imageWrapperOpts);
            }
	
	}
	
	
	//html hors image
	$htmlText = '';
	
	//Titre affiché en span car utilisé dans un container
	if(isset($name)) if($name) $htmlText.= get_partial('global/schema/DataType/Text', array('value' => $name, 'itemprop' => 'name', 'container' => 'span.title'));
	//Gestion de la date de création
	if(isset($dateCreated)) if($dateCreated) $htmlText.= get_partial('global/dateWrapperShort', array('dateCreated' => $dateCreated));
	
	//englobage dans un container si non vide
	if($htmlText != null) $htmlText = _tag('span.subWrapper', $htmlText);

	//Chapeau de l'article si présent
	if(isset($description)) if($description) {
		//ajout de la longueur de la description
		$descriptionOpt = array('value' => $description, 'itemprop' => 'description', 'container' => 'span.teaser');
		if(isset($descriptionLength)) $descriptionOpt['valueLength'] = $descriptionLength;
		//insertion description
		$htmlText.= get_partial('global/schema/DataType/Text', $descriptionOpt);
	}
	
	//insertion dans un wrapper si une image est présente
	if($isImage) $htmlText = _tag('span.wrapper', $htmlText);
	
	
	
	
	//inclusion dans le lien si nécessaire
	if(isset($url)) {
		$htmlLink = _link($url)->text($htmlImage . $htmlText)->set('.link_box');
		if(isset($name)) $htmlLink->title($name);
		$html.= $htmlLink;
	}else{
		$html.= $htmlImage . $htmlText;
	}
	
	
	
	
	//ajout de liens de navigation si nécessaire
	if(isset($navigationElements)) {
		$html.= get_partial('global/navigationWrapper', array(
														'placement' => 'bottom',
														'elements' => $navigationElements,
														'container' => 'span'
														));
	}
	
}else{
	//contenu du header de l'article
	$htmlHeader = '';
	
		//La rubrique l'article, à savoir Actualités, Chiffre, Dossier, etc
		if(isset($rubrique)) $htmlHeader.= _tag('h2.category', array('itemprop' => 'articleSection'), $rubrique);
		//La section de l'article, à savoir Social, Juridique, Fiscal, etc
		if(isset($section)) $htmlHeader.= _tag('h3.section', array('itemprop' => 'articleSection'), $section);
                if((!isset($photo)) || ($photo == true)){
		//on affiche l'image que si elle est effectivement présente
		if($isImage && isset($image)){
			//dimensions de l'image
			$imageGridWidth = sidSPLessCss::getLessParam('thumbContent_col');
			$imageGridHeight = sidSPLessCss::getLessParam('thumbContent_bl');
			//options de l'image
			$imageWrapperOpts = array(
									'image'	=>	$image,
									'container' => 'div.imageFullWrapper',
									'width'	=>	spLessCss::gridGetWidth($imageGridWidth,0),
									'height'=>	spLessCss::gridGetHeight($imageGridHeight,0)
									);
			//ajout du nom de l'article dans la balise Alt de l'image
			if(isset($name)) $imageWrapperOpts['alt'] = $name;
		
			//Appel du partial d'image
			$htmlHeader.= get_partial('global/schema/DataType/Image', $imageWrapperOpts);
		}
                }

		//Le titre de l'article, devant toujours être l'unique H1 dans la page
		if(isset($name)) if($name) $htmlHeader.= get_partial('global/schema/DataType/Text', array('value' => $name, 'itemprop' => 'name', 'container' => 'h1.title'));

		//Chapeau de l'article si présent
		if(isset($description)) if($description) $htmlHeader.= get_partial('global/schema/DataType/Text', array('value' => $description, 'itemprop' => 'description', 'container' => 'span.teaser'));

		//Gestion de la date avec plusieurs possibilités (dateCreated, dateModified, etc)
		if(isset($dateCreated)) if($dateCreated) $htmlHeader.= get_partial('global/dateWrapperFull', array('node' => $node));
		
	//affichage du header de l'article si non vide
	if($htmlHeader != null) $html.= _tag('header.contentHeader', $htmlHeader);
	
	
	
	
	//affichage du contenu de la page
	if(isset($articleBody))	$html.= _tag('section.contentBody', array('itemprop' => 'articleBody'), $articleBody);
	
	
	// rajout stef
	if($uploadFile != ''){
	//contenu du footer de l'article
        $htmlFooter ="";   
	$htmlFooter = _tag('h5.title',__('Download file, click the link below'));
        $htmlFooter.= _link($uploadFile)->text($uploadFileTitle);
        }
        else{
        //contenu du footer de l'article
	$htmlFooter = '';
        }
	// fin rajout stef
	//ajout de liens de navigation si nécessaire
	if(isset($navigationElements)) {
		$htmlFooter.= get_partial('global/navigationWrapper', array(
														'placement' => 'bottom',
														'elements' => $navigationElements
														));
	}
	
	//affichage du footer de l'article si non vide
	if($htmlFooter != null) $html.= _tag('footer.contentFooter', $htmlFooter);
}

//englobage dans un container
if(isset($container)) $html = _tag($container, $ctnOpts, $html);

//Affichage html de sortie
echo $html;