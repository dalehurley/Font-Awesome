<?php
use_helper('Date');
// var partial : $article, $titreBloc
//if (isset($article)) {
//    if ($textLength == false)
//	$textLength = 0;
//    if (!isset($textEnd))
//	$textEnd = ' ...';
//	 
// Si il n'y a que 1 article et que le titreBloc est vide, on enlève le titre de l'article pour le mettre dans la partie supérieure du Bloc
 
//     if ($titreBloc == true) {
//            echo _tag('h2.title itemprop="name"', $articles->getTitle());
//        }
        
echo '<article itemscope itemtype="http://schema.org/Article">';
if ($titreBloc == true) {
            echo _tag('h2.title itemprop="name"', $articles->getTitle());
        }

	    $imgLink = '/uploads/' . $articles->getImage();
//on vérifie que l'image existe
    $imgExist = is_file(sfConfig::get('sf_web_dir') . $imgLink);

	// on teste si le fichier image est présent sur le serveur avec son chemin absolu
	if ($imgExist) {
		echo _open('div.imageFullWrapper');
			echo _media($imgLink)
						->set('.image itemprop="image"')
						->alt($articles->getTitle())
						//redimenssionnement propre lorsque l'image sera en bibliothèque
						->width(spLessCss::gridGetContentWidth());
						//->height(spLessCss::gridGetHeight(14,0))
		echo _close('div');
	}
	
	echo _tag('p.teaser', $articles->getResume());
        echo _tag('section.contentBody itemprop="articleBody"', $articles->getText());
        echo _tag('p.meta', 'Mis à jour le '.format_date($articles->updated_at, "D"));

//Fermeture de l'article
echo _close('article');
?>
