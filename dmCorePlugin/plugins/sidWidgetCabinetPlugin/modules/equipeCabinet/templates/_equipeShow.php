<?php
$html = '';

if($titreBloc != null) $html.= get_partial('global/titleWidget', array('title' => $titreBloc));

// vars  $equipes, $titreBloc, $nomRubrique
if (count($equipes)) { // si nous avons des actu articles
	
	//afin de séparer les affichages par implantations on créé un nouveau tableau
	//dont chaque clé correspond à une implantation et contient un tableau des membres associés
	$implantations = array();
	foreach ($equipes as $equipe) {
		$implantationId = dmString::slugify($equipe->ImplentationId);
		//remplissage d'un nouveau tableau à chaque implantation
		$implantations[$implantationId]['ville'] = $equipe->ImplentationId->ville;
		$implantations[$implantationId]['equipes'][] = $equipe;
	}
	
	//compteur
	$implantationCount = 0;
	$implantationMaxCount = count($implantations);
	
	//affichage des équipes
	foreach ($implantations as $implantationId => $implantation) {
		//incrémentation compteur
		$implantationCount++;
		
		//options du container
		$wrapperOpt = array();
		//gestion des classes de début et de fin
		if($implantationCount == 1)						$wrapperOpt['class'][] = 'first';
		if($implantationCount >= $implantationMaxCount)	$wrapperOpt['class'][] = 'last';
		
		//ouverture du container
		$html.= _open('section.supWrapper.clearfix', $wrapperOpt);
		
		//affichage de la ville d'implantation
		$html.= get_partial('global/titleSupWrapper', array('title' => (__('Implantation') . '&#160;:&#160;'. $implantation['ville'])));
		
		//ouverture de la liste
		$html.= _open('ul.elements');
		
		//compteur
		$count = 0;
		$maxCount = count($implantation['equipes']);
		
		//affichage des membres de chaque implantation
		foreach ($implantation['equipes'] as $equipe) {
			//incrémentation compteur
			$count++;
			
			//options des personnes
			$personOpt = array(
							'name' => $equipe->getTitle(),
							'description' => $equipe->getText(),
							'image' => $equipe->getImage(),
							'email' => $equipe->getEmail(),
							'faxNumber' => $equipe->getFax(),
							'telephone' => $equipe->getTel(),
							'jobTitle' => $equipe->getStatut(),
							'container' => 'li.element',
							'count' => $count,
							'maxCount' => $maxCount
							);
			//rajout de la responsabilité seulement si présent
			if(array_key_exists($equipe->id, $nomRubrique)) $personOpt['contactType'] = $nomRubrique[$equipe->id];
			
			$html.= get_partial('global/schema/Thing/Person', $personOpt);
		}
		
		//fermeture de la liste et du container
		$html.= _close('ul.elements');
		$html.= _close('section.supWrapper');
	}
}else{
	$html.= "Aucun membre de l'équipe n'est présenté.";
}

//affichage html en sortie
echo $html;