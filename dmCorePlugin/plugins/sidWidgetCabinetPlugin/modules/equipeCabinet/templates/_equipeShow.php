<?php
// $vars = $equipes, $titreBloc, $nomRubrique, $withImage
$html = '';

echo _tag('h4.title',$titreBloc);
foreach ($equipes as $equipe) { echo $equipe->ImplentationId->ville.' - '.$equipe->ImplentationId->siege_social.'<br />';}
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
?>

<h4 class="title">Notre équipe, vos conseils</h4>
<section class="supWrapper clearfix first">
    <section class="supWrapper clearfix">
        <section class="supWrapper clearfix last">
            <h3 class="title">Implantation&nbsp;:&nbsp;Poitiers</h3>
            <ul class="elements">
                <li class="element itemscope Person first" itemtype="http://schema.org/Person" itemscope="itemscope">
                <li class="element itemscope Person" itemtype="http://schema.org/Person" itemscope="itemscope">
                <li class="element itemscope Person" itemtype="http://schema.org/Person" itemscope="itemscope">
                <li class="element itemscope Person last" itemtype="http://schema.org/Person" itemscope="itemscope">
                    <span class="imageWrapper">
                        <img class="image" height="60" width="60" src="/uploads/equipe/.thumbs/portrait_homme04_7741d2.jpg" itemprop="image" alt="Monsieur LeSecrétaire">
                    </span>
                    <span class="wrapper">
                        <span class="itemprop name" itemprop="name">Monsieur LeSecrétaire</span>
                        <span class="itemprop jobTitle" itemprop="jobTitle">Secrétariat</span>
                        <span class="contactPoints itemscope ContactPoint" itemtype="http://schema.org/ContactPoint" itemscope="itemscope" itemprop="contactPoints">
                            <span class="itemprop contactType">
                                <span class="type" title="Responsable en">Responsable en</span>
                                <span class="separator">&nbsp;:&nbsp;</span>
                                <span class="value" itemprop="contactType">Fiscal - Création d'entreprise - Multimédia</span>
                            </span>
                            <span class="itemprop email">
                                <span class="type" title="Email">Email</span>
                                <span class="separator">&nbsp;:&nbsp;</span>
                                <span class="value">
                                    <a class="link" itemprop="email" href="mailto:lesecretaire@ec-tenor.com">lesecretaire@ec-tenor.com</a>
                                </span>
                            </span>
                            <span class="itemprop telephone">
                                <span class="type" title="Téléphone">Téléphone</span>
                                <span class="separator">&nbsp;:&nbsp;</span>
                                <span class="value" itemprop="telephone">05 49 60 20 63</span>
                            </span>
                        </span>
                        <span class="itemprop description" itemprop="description"> Phasellus eu mauris diam. Sed commodo facilisis tempus. Integer enim justo, vehicula sit amet dignissim quis, tincidunt in sapien. Etiam porttitor dolor in justo laoreet facilisis ullamcorper enim tristique. Nunc sodales imperdiet eros eget tincidunt. Donec eleifend ultrices leo, at aliquam felis blandit nec. Etiam et diam nec est gravida accumsan sit amet eu ipsum. </span>
                    </span>
                </li>
            </ul>
        </section>