<?php
// $vars = $titreBloc, $nomRubrique, $withImage, $arrayVilles, $width
$html = '';

echo _tag('h4.title',$titreBloc);
// vars  $equipes, $titreBloc, $nomRubrique
if (count($arrayVilles)) { // si nous avons des actu articles
	
	//afin de séparer les affichages par implantations on créé un nouveau tableau
	//dont chaque clé correspond à une implantation et contient un tableau des membres associés
//	$implantations = array();
        
        
//	foreach ($equipes as $equipe) {
//		$implantationId = dmString::slugify($equipe->ImplentationId);
//		//remplissage d'un nouveau tableau à chaque implantation
//		$implantations[$implantationId]['ville'] = $equipe->ImplentationId->ville;
//		$implantations[$implantationId]['equipes'][] = $equipe;
//	}
//	
//	//compteur
//	$implantationCount = 0;
//	$implantationMaxCount = count($implantations);
//	
//	//affichage des équipes
//	foreach ($implantations as $implantationId => $implantation) {
//		//incrémentation compteur
//		$implantationCount++;
//		
//		//options du container
//		$wrapperOpt = array();
//		//gestion des classes de début et de fin
//		if($implantationCount == 1)						$wrapperOpt['class'][] = 'first';
//		if($implantationCount >= $implantationMaxCount)	$wrapperOpt['class'][] = 'last';
//		
//		//ouverture du container
//		$html.= _open('section.supWrapper.clearfix', $wrapperOpt);
//		
//		//affichage de la ville d'implantation
//		$html.= get_partial('global/titleSupWrapper', array('title' => (__('Implantation') . '&#160;:&#160;'. $implantation['ville'])));
//		
//		//ouverture de la liste
//		$html.= _open('ul.elements');
//		
//		//compteur
//		$count = 0;
//		$maxCount = count($implantation['equipes']);
//		
//		//affichage des membres de chaque implantation
//		foreach ($implantation['equipes'] as $equipe) {
//			//incrémentation compteur
//			$count++;
//			
//			//options des personnes
//			$personOpt = array(
//							'name' => $equipe->getTitle(),
//							'description' => $equipe->getText(),
//							'image' => $equipe->getImage(),
//							'email' => $equipe->getEmail(),
//							'faxNumber' => $equipe->getFax(),
//							'telephone' => $equipe->getTel(),
//							'jobTitle' => $equipe->getStatut(),
//							'container' => 'li.element',
//							'count' => $count,
//							'maxCount' => $maxCount
//							);
//			//rajout de la responsabilité seulement si présent
//			if(array_key_exists($equipe->id, $nomRubrique)) $personOpt['contactType'] = $nomRubrique[$equipe->id];
//			
//			$html.= get_partial('global/schema/Thing/Person', $personOpt);
//		}
//		
//		//fermeture de la liste et du container
//		$html.= _close('ul.elements');
//		$html.= _close('section.supWrapper');
//	}
//}else{
//	$html.= "Aucun membre de l'équipe n'est présenté.";
//}
//
////affichage html en sortie
//echo $html;

// initialisation des variables pour les class first et last
$ville = 1;
$countVille = count($arrayVilles);
// initialisation des variables pour les class first et last

    foreach ($arrayVilles as $key => $arrayVille) {
        // condition pour gérer les class des listings
        if ($ville == 1){
            $incrementVille = ' first';
            if($ville == $countVille) $incrementVille = ' first last';
            }
        elseif ($ville == $countVille)
            $incrementVille = ' last';
        else
            $incrementVille = '';
        // condition pour gérer les class des listings
        
        echo _open('section', array('class' => 'supWrapper clearfix' . $incrementVille));
            echo _tag('h3', array('class' => 'title'), __('Implantation') . '&nbsp;:&nbsp;' . $key);
            echo _open('ul', array('class' => 'elements'));
        
            // initialisation des variables pour les class first et last
            $nbEquipe = 1;
            $countEquipe = count($arrayVille);
            // initialisation des variables pour les class first et last
            foreach ($arrayVille as $key=>$equipe) {
                // condition pour gérer les class des listings
                if ($nbEquipe == 1){
                    $incrementEquipe = ' first';
                    if($nbEquipe == $countEquipe) $incrementEquipe = ' first last';
                    }
                elseif ($nbEquipe == $countEquipe)
                    $incrementEquipe = ' last';
                else
                    $incrementEquipe = '';
                // condition pour gérer les class des listings

                echo _open('li', array('class' => 'element itemscope Person ' . $incrementEquipe, 'itemtype' => 'http://schema.org/Person', 'itemscope' => 'itemscope', 'id' => dmString::slugify($equipe->getFirstName().'-'.$equipe->getName())));

                    if (($withImage == TRUE) && $equipe->getImage()->checkFileExists() == true) {
                        echo _tag('span', array('class' => 'imageWrapper'), _media($equipe->getImage())->width($width)->method('scale')->alt($equipe->getFirstName().'-'.$equipe->getName())->set('.image itemprop="image"'));
                    };
                    echo _open('span', array('class' => 'wrapper'));
                        echo _tag('span', array('class' => 'itemprop name', 'itemprop' => 'name'), __($equipe->getTitle()).' '.$equipe->getFirstName().' '.$equipe->getName());
                        echo _tag('span', array('class' => 'itemprop jobTitle', 'itemprop' => 'jobTitle'), $equipe->getStatut());
                        echo _open('span', array('class' => 'contactPoints itemscope ContactPoint', 'itemtype' => 'http://schema.org/ContactPoint', 'itemscope' => 'itemscope', 'itemprop' => 'contactPoints'));
                            if (isset($nomRubrique[$equipe->id])) {
                                echo _open('span', array('class' => 'itemprop contactType'));
                                echo _tag('span', array('class' => 'type', 'title' => __('Responsable in')), __('Responsable in'));
                                echo _tag('span', array('class' => 'separator'), '&nbsp;:&nbsp;');
                                echo _tag('span', array('class' => 'value', 'itemprop' => 'contactType'), $nomRubrique[$equipe->id]);
                                echo _close('span');
                            };
                            if ($equipe->email != NULL) {
                                echo _open('span', array('class' => 'itemprop email'));
                                echo _tag('span', array('class' => 'type', 'title' => __('Email')), __('Email'));
                                echo _tag('span', array('class' => 'separator'), '&nbsp;:&nbsp;');
                                echo _open('span', array('class' => 'value'));
                                echo _link('mailto:' . $equipe->email)->set(' itemprop="email"')->text($equipe->email);
                                echo _close('span');
                                echo _close('span');
                            };
                            if ($equipe->tel != NULL) {
                                echo _open('span', array('class' => 'itemprop telephone'));
                                echo _tag('span', array('class' => 'type', 'title' => __('Phone')), __('Phone'));
                                echo _tag('span', array('class' => 'separator'), '&nbsp;:&nbsp;');
                                echo _tag('span', array('class' => 'value', 'itemprop' => 'telephone'), $equipe->tel);
                                echo _close('span');
                            };
                            if ($equipe->mobile != NULL) {
                                echo _open('span', array('class' => 'itemprop cellphone'));
                                echo _tag('span', array('class' => 'type', 'title' => __('Cellphone')), __('Cellphone'));
                                echo _tag('span', array('class' => 'separator'), '&nbsp;:&nbsp;');
                                echo _tag('span', array('class' => 'value', 'itemprop' => 'cellphone'), $equipe->mobile);
                                echo _close('span');
                            };
                        echo _close('span');
                        echo _tag('span', array('class' => 'itemprop description', 'itemprop' => 'description'), $equipe->getText());
                    echo _close('span');
                echo _close('li');
                $nbEquipe++;
                };
            echo _close('ul');
        echo _close('section');
        $ville++;
    }
	
}else{
	$html.= "Aucun membre de l'équipe n'est présenté.";
}