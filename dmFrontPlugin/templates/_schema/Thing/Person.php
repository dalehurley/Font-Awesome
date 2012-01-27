<?php
/*
 * Person.php
 * v1.1
 * http://schema.org/Person
 * 
 * Variables disponibles :
 * $node
 * $container
 * $isLight
 * 
 * Properties from Thing :
 * $description
 * $image
 * $name
 * $url
 * 
 * Properties from Person : 
 * $additionalName	Text					An additional name for a Person, can be used for a middle name.
 * $address			PostalAddress			Physical address of the item.
 * $affiliation		Organization			An organization that this person is affiliated with. For example, a school/university, a club, or a team.
 * $alumniOf		EducationalOrganization An educational organizations that the person is an alumni of.
 * $awards			Text					Awards won by this person or for this creative work.
 * $birthDate		Date					Date of birth.
 * $children		Person					A child of the person.
 * $colleagues		Person					A colleague of the person.
 * $contactPoints	ContactPoint			A contact point for a person or organization.
 * $deathDate		Date					Date of death.
 * $email			Text					Email address.
 * $familyName		Text					Family name. In the U.S., the last name of an Person. This can be used along with givenName instead of the Name property.
 * $faxNumber		Text					The fax number.
 * $follows			Person					The most generic uni-directional social relation.
 * $gender			Text					Gender of the person.
 * $givenName		Text					Given name. In the U.S., the first name of a Person. This can be used along with familyName instead of the Name property.
 * $homeLocation	Place or ContactPoint 	A contact location for a person's residence.
 * $honorificPrefix	Text					An honorific prefix preceding a Person's name such as Dr/Mrs/Mr.
 * $honorificSuffix	Text					An honorific suffix preceding a Person's name such as M.D. /PhD/MSCSW.
 * $interactionCountText					A count of a specific user interactions with this item—for example, 20 UserLikes, 5 UserComments, or 300 UserDownloads. The user interaction type should be one of the sub types of UserInteraction.
 * $jobTitle		Text					The job title of the person (for example, Financial Manager).
 * $knows			Person					The most generic bi-directional social/work relation.
 * $memberOf		Organization			An organization to which the person belongs.
 * $nationality		Country					Nationality of the person.
 * $parents			Person					A parents of the person.
 * $performerIn		Event					Event that this person is a performer or participant in.
 * $relatedTo		Person					The most generic familial relation.
 * $siblings		Person					A sibling of the person.
 * $spouse			Person					The person's spouse.
 * $telephone		Text					The telephone number.
 * $workLocation	Place or ContactPoint	A contact location for a person's place of work.
 * $worksFor		Organization			Organizations that the person works for.
 * 
 */
//Définition du type (inséré dans le container si présent)
$itemType = "Person";

//récupération des valeurs dans la node par les getters par défaut
$includeDefault = sfConfig::get('dm_front_dir') . '/templates/_schema/_varsDefaultGetters.php';
include $includeDefault;

//Composition du html de sortie
$html = '';




//html image
$htmlImage = '';

//on affiche l'image que si elle est effectivement présente
if($isImage && isset($image)){
	//dimensions de l'image
	$imageGridWidth = ($isLight) ? spLessCss::getLessParam('thumbS_col') : spLessCss::getLessParam('thumbM_col');
	$imageGridHeight = ($isLight) ? spLessCss::getLessParam('thumbS_bl') : spLessCss::getLessParam('thumbM_bl') * 2;
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




//html hors image
$htmlText = '';

//if(isset($name) || isset($jobTitle)) {
	if(isset($name)) if($name) $htmlText.= get_partial('global/schema/DataType/Text', array('value' => $name, 'itemprop' => 'name'));
	if(isset($jobTitle)) if($jobTitle) $htmlText.= get_partial('global/schema/DataType/Text', array('value' => $jobTitle, 'itemprop' => 'jobTitle'));
	//englobage dans un container
	//$dash . 
	//$htmlText = _tag('span.subWrapper', $htmlText);
//}

//Properties from ContactPoint :
$contactPointOpt = array('container' => 'span.contactPoints itemprop="contactPoints"', 'url' => false, 'isLight' => $isLight);
if(isset($contactType) && !$isLight)$contactPointOpt['contactType']	= $contactType;
if(isset($email))					$contactPointOpt['email']		= $email;
if(isset($faxNumber))				$contactPointOpt['faxNumber']	= $faxNumber;
if(isset($telephone))				$contactPointOpt['telephone']	= $telephone;
$htmlText.= get_partial('global/schema/Thing/Intangible/StructuredValue/ContactPoint', $contactPointOpt);

//__('Responsable in')

//affichage de la description
if(isset($description) && !$isLight) $htmlText.= get_partial('global/schema/DataType/Text', array('value' => $description, 'itemprop' => 'description'));

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


//englobage dans un container
if(isset($container)) $html = _tag($container, $ctnOpts, $html);

//Affichage html de sortie
echo $html;