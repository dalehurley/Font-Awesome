<?php
//chargement feuille de style spécifique
use_stylesheet(sfConfig::get('sf_css_path_template'). '/Widgets/SpecifiquesBaseEditorialeFilActualite/SpecifiquesBaseEditorialeFilActualite.css');

$html = '';

// vars : $section, $titreBloc, $titreLien, $longueurTexte, $articles, $arrayRubrique, $photo
if (count($articles)) { // si nous avons des actu articles
	
	$html.= get_partial('global/titleWidget', array('title' => $titreBloc));
	
	//ouverture du listing
    $html.= _open('ul.elements');
	
    foreach ($articles as $article) {
		//création d'un tableau de liens à afficher
		$elements = array();
		$elements[] = array('title' => $titreLien . '&#160;' . $arrayRubrique[$article->filename], 'linkUrl' => $article->Section);
		
		$html.= get_partial('global/publicationListElement', array(
												'node' => $article,
												'image' => '/_images/lea' . $article->filename . '-p.jpg',
												'count' => 1,
												'maxCount' => 1,
												'teaser' => $article->getChapeau(),
												'teaserLength' => $longueurTexte,
												'navigationElements' => $elements,
												'linkUrl' => $article
												));
    }
	
    //fermeture du listing
    $html.= _close('ul.elements');
    
}

//affichage html en sortie
echo $html;