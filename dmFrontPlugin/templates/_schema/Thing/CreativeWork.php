<?php
/*
 * CreativeWork.php
 * v1.0
 * http://schema.org/CreativeWork
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
 */
//Définition du type (inséré dans le container si présent)
$itemType = "CreativeWork";

//récupération des valeurs dans la node par les getters par défaut
$includeDefault = sfConfig::get('dm_front_dir') . '/templates/_schema/_varsDefaultGetters.php';
include $includeDefault;

//Composition du html de sortie
$html = '';

//On organise le contenu différemment selon le type de version d'affichage
if($isLight) {
	//voir plus tard, inutilisé
}else{
	//voir plus tard, inutilisé
}

//inclusion dans le lien si nécessaire
if(isset($url)) $html = _link($url)->text($html)->title($name)->set('.link_box');

//englobage dans un container
if(isset($container)) $html = _tag($container, $ctnOpts, $html);

//Affichage html de sortie
echo $html;