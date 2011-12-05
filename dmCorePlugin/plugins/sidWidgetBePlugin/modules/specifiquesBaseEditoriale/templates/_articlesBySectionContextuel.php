<?php
// vars : $articles, $lien, $titreBloc, $longueurTexte, $rubrique, $section
//if (count($articles)) { // si nous avons des actu articles
//    if($nbArticles == 1){
//        if ($titreBloc != true) {
//            echo _tag('h4.title', $rubrique);
//        }
//        else  echo _tag('h4.title', $titreBloc);
//    }
//    echo _tag('h4.title', $titreBloc);
//    echo _open('ul.elements');
//    foreach ($articles as $i=>$article) {
//        echo "<h4 class='title'>".$article->getTitle().'</h4>';
//        echo "<span class='teaser'>".$article->getChapeau().'</span>';
//        echo _link($article);
//        if($lien[$i] != null){
//        echo "<a class='link' href=".$article.'>'.$lien[$i].'</a>';
//        }
//        echo '<hr/>';
//
//	include_partial("objectPartials/articleContextuel", array("lien" => $lien,"articles" => $articles,"textLength" => $longueurTexte,"textEnd" => '(...)',"photoArticle" => $photo,'titreBloc' => $titreBloc));
////
//    }
//    echo _close('ul');
//
//    echo _open('div.navigation.navigationBottom');
//	echo _open('ul.elements');
//	    echo _open('li.element');
//		echo _link('sidActuArticle/list')->text($titreLien);
//	    echo _close('li');
//	echo _close('ul');
//    echo _close('div');
//} // sinon on affiche rien
//<?php
// vars : $section, $titreBloc, $titreLien, $longueurTexte, $articles, $arrayRubrique, $photo
if (count($articles)) { // si nous avons des actu articles

    echo _tag('h4.title', $titreBloc);
    echo _open('ul.elements');
    foreach ($articles as $article){
        $html ="";
        echo _open('li.element');
        if ($titreBloc != true) {
            $html.= _tag('h4.title', $rubrique);
        }
        else  $html.= _tag('h4.title', $titreBloc);
        
        $html .= _open('span.wrapper');
        $html .= _tag('span.title', '' . $article) ;
        $html .= _tag('span.teaser itemprop="description"', stringTools::str_truncate($article->getChapeau(), $longueurTexte, '(...)', true));
        $html .= _close('span.wrapper');
        
echo _link($article)->text($html)->set('.link_box');
echo _open('div.navigation.navigationBottom');
//echo _link($article->Section)->text($section.' - '.$rubrique);
//	echo _close('ul');
echo _close('div');
echo _close('li');
//	include_partial("objectPartials/filActualite", array("article" => $article,"textLength" => $longueurTexte,"textEnd" => '(...)','titreBloc' => $titreBloc, "titreLien" => $titreLien,"arrayRubrique" => $arrayRubrique,"photo" => $photo));

    }
    echo _close('ul');
}