<?php
// vars : $articles, $titreBloc
if(count($articles)){
	
	$pubOpts = array();
	if(count($articles))	$pubOpts['node']		= $articles;
	if($titreBloc != null)	$pubOpts['category']	= $titreBloc;
							$pubOpts['title']		= $articles->getTitle();
							$pubOpts['image']		= '/uploads/' . $articles->getImage();
							$pubOpts['content']		= $articles->getText();
	
	include_partial('global/publicationShow', $pubOpts);
}else{
	include_partial('global/publicationShow', array(
													'content' => '{{actualites_du_cabinet}}'
												));
}


/*
if (count($articles)) { // si nous avons des actu articles

        if ($titreBloc != true) {
            echo _tag('h4.title', $articles->getTitle());
        }
        else {
            echo _tag('h4.title', $titreBloc);
        }
    
//    foreach ($articles as $article) {

	include_partial("objectPartials/actuArticleShow", array("articles" => $articles,'titreBloc' => $titreBloc));

//    }
    
} // sinon on affiche la constante de la page concernée
else echo'{{actualites_du_cabinet}}';
*/
