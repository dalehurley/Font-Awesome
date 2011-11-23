<?php
if (count($articles)) { // si nous avons des actu articles
//    if($nbArticles == 1){
//        if ($titreBloc != true) {
//            echo _tag('h4.title', current($articles));
//        }
//        else  echo _tag('h4.title', $titreBloc);
//    }
    echo _tag('h4.title', $titreBloc);
    echo _open('ul.elements');
//    foreach ($articles as $i=>$article) {
//        echo "<h4 class='title'>".$article->getTitle().'</h4>';
//        echo "<span class='teaser'>".$article->getChapeau().'</span>';
//        if($lien[$i] != null){
//        echo "<a class='link' href=".$article.'>'.$lien[$i].'</a>';
//        }
//        echo '<hr/>';
//
	include_partial("objectPartials/articleContextuel", array("lien" => $lien,"articles" => $articles,"textLength" => $longueurTexte,"textEnd" => '(...)',"photoArticle" => $photo,'titreBloc' => $titreBloc));
//
    }
    echo _close('ul');
//
//    echo _open('div.navigation.navigationBottom');
//	echo _open('ul.elements');
//	    echo _open('li.element');
//		echo _link('sidActuArticle/list')->text($titreLien);
//	    echo _close('li');
//	echo _close('ul');
//    echo _close('div');
//} // sinon on affiche rien
