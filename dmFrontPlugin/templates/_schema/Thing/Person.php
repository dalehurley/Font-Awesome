<?php
/*
 * Person.php
 * v1.0
 * http://schema.org/Person
 * 
 * Variables disponibles :
 * $node
 * $container
 * $rubrique
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

//ouverture du container
if(isset($container)) $html.= _open($container, $ctnOpts);

//Properties from Thing :
$thingOpt = array();
if(isset($node))		$thingOpt['node']			= $node;
						$thingOpt['description']	= false;
if(isset($image))		$thingOpt['image']			= $image;
						$thingOpt['name']			= false;
						$thingOpt['url']			= false;
$html.= get_partial('global/schema/Thing', $thingOpt);



//on englobe le contenu dans un wrapper si une image est présente
if($isImage) $html.= _open('span.wrapper');
	
	//on englobe le nom et la fonction dans un subWrapper
	if(isset($name) || isset($jobTitle)) {
		$html.= _open('div.subWrapper');
			if(isset($name)) if($name) $html.= get_partial('global/schema/DataType/Text', array('value' => $name, 'itemprop' => 'name'));
			if(isset($jobTitle)) if($jobTitle) $html.= _tag('span.separator', $separator) . get_partial('global/schema/DataType/Text', array('value' => $jobTitle, 'itemprop' => 'name'));	
		$html.= _close('div');
	}
	
	//Ajout de la rubrique
	if(isset($rubrique) && !$isLight) $html.= get_partial('global/schema/DataType/Text', array('type' => __('Responsable in'), 'value' => $rubrique));
	
	//options d'affichage de l'email
	if(isset($email)) if($email) {
		$emailOpt = array(
					'type'		=> __('Email'),
					'value'		=> $email,
					'itemprop' => 'email'
				);
		//si l'URL n'est pas définit pour la personne alors on peut rajouter l'email dans l'affichage
		if(!isset($url)) $emailOpt['url'] = 'mailto:' . $email;
		$html.= get_partial('global/schema/DataType/Text', $emailOpt);
	}
	
	//affichage de la description
	if(isset($description) && !$isLight) $html.= get_partial('global/schema/DataType/Text', array('value' => $description, 'itemprop' => 'description'));
	
//fermeture du container de contenu
if($isImage) $html.= _close('span.wrapper');

//fermeture du container
if(isset($container)) $html.= _close($container);

//Affichage html de sortie
echo $html;