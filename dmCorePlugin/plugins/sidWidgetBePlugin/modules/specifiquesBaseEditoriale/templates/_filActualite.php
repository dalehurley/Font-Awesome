<?php
//chargement feuille de style spécifique
use_stylesheet(sfConfig::get('sf_css_path_template'). '/Widgets/SpecifiquesBaseEditorialeFilActualite/SpecifiquesBaseEditorialeFilActualite.css');

// vars : $section, $titreBloc, $titreLien, $longueurTexte, $articles, $arrayRubrique, $photo
if (count($articles)) { // si nous avons des actu articles

    echo _tag('h4.title', $titreBloc);
    echo _open('ul.elements');
    foreach ($articles as $article){

	include_partial("objectPartials/filActualite", array("article" => $article,"textLength" => $longueurTexte,"textEnd" => '(...)','titreBloc' => $titreBloc, "titreLien" => $titreLien,"arrayRubrique" => $arrayRubrique,"photo" => $photo));

    }
    echo _close('ul');
    
}