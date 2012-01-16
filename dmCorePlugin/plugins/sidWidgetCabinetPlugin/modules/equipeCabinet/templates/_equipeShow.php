<?php
$html = '';

if($titreBloc != null) $html.= _tag('h4.title', $titreBloc);

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
	
	//affichage des équipes
	foreach ($implantations as $implantationId => $implantation) {
		//ouverture du container
		$html.= _open('div.supWrapper');
		//affichage de la ville d'implantation
		$html.= _tag('h5.title', __('Implantation') . '&#160;: '. $implantation['ville']);
		//ouverture de la liste
		$html.= _open('ul.elements');
		
		//compteur
		$count = 0;
		$maxCount = count($implantation['equipes']);
		
		//affichage des membres de chaque implantation
		foreach ($implantation['equipes'] as $equipe) {
			//incrémentation compteur
			$count++;
			
			$html.= get_partial('global/publicationListElement', array(
													'node' => $equipe,
													'itemType' => 'Person',
													'title' => $equipe->getTitle(),
													'image' => $equipe->getImage(),
													'rubrique' => $nomRubrique[$equipe->id],
													'count' => $count,
													'maxCount' => $maxCount
													));
		}
		
		//fermeture de la liste et du container
		$html.= _close('ul.elements');
		$html.= _close('div.supWrapper');
	}
}else{
	$html.= "Aucun membre de l'équipe n'est présenté.";
}

//affichage html en sortie
echo $html;