<?php
/*
 * _schemaContactPoint.php
 * v0.1
 * Permet d'afficher un point de contact
 * http://schema.org/ContactPoint
 * 
 * Variables disponibles :
 * $node
 * $disableLink		//permet de désactiver le lien de l'email (utile quand encapsulé dans un lien)
 * 
 * Properties from ContactPoint :
 * 
 * $contactType
 * $email
 * $faxNumber
 * $telephone
 * 
 */
$html = '';

//valeur par défaut
if(!isset($disableLink)) $disableLink = false;

//on affecte les valeurs par défaut en fonction de la node passée en paramètre
if(isset($node)) {
	//si les valeurs ne sont pas explicitement définies on récupère la valeur dans la node, sauf si la valeur est débrayée par FALSE
	if(!isset($email)) {
		if($email)
			try { $email = $node->getEmail(); }
			catch(Exception $e) { $email = null; }
	}
	if(!isset($faxNumber)) {
		if($faxNumber)
			try { $faxNumber = $node->getTel(); }
			catch(Exception $e) { $faxNumber = null; }
	}
	if(!isset($telephone)) {
		if($telephone)
			try { $telephone = $node->getTel(); }
			catch(Exception $e) { $telephone = null; }
	}
}

//Ajout des données présentes
if($telephone != null) $html.= get_partial('global/schemaTypeValue', array(
														'itemType'	=> 'telephone',
														'type'		=> __('Phone'),
														'value'		=> $telephone
													));

if($faxNumber != null) $html.= get_partial('global/schemaTypeValue', array(
														'itemType'	=> 'faxNumber',
														'type'		=> __('Fax'),
														'value'		=> $faxNumber
													));

if($email != null) {
	$emailOpt = array(
					'itemType'	=> 'email',
					'type'		=> __('Email'),
					'value'		=> $email
				);
	
	//si l'URL n'est pas définit pour le listing alors on peut rajouter l'email dans l'affichage
	if(!$disableLink) $emailOpt['linkUrl'] = 'mailto:' . $email;
	$htmlLi.= get_partial('global/schemaTypeValue', $emailOpt);
}

//affichage html en sortie
echo $html;