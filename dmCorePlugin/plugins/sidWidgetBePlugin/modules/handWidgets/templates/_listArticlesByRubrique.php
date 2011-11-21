<?php  // var $articles - var $rubriqueTitle - var $rubrique - var $photoArticle - var $titreBloc
//Gestion affichage du titre du block
//Si un article affiché, son titre est affiché, sinon la rubrique est affichée
if ((count($articles) == 1) && ($titreBloc == true)) {
    foreach ($articles as $article) {
        echo _tag('h4.title', $article->getTitle());
    }
} else {
    echo _tag('h4.title', $rubriqueTitle);
}

//Affichage des articles
echo _open('ul.elements');
	foreach ($articles as $article) {
		echo '<li class="element" itemscope itemtype="http://schema.org/Article">';
			//lien vers l'image
			$imgLink = '/_images/lea' . $article->filename . '-p.jpg';
			//on vérifie que l'image existe
			$imgExist = is_file(sfConfig::get('sf_web_dir') . $imgLink);

			//composition du html contenu dans le lien (ne peux contenir que des span)
			$html = '';

			if ($photoArticle == true && $imgExist) {
				$html.= _open('span.imageWrapper');
					$html.= _media($imgLink)
							->set('.image itemprop="image"')
							->alt($article->getTitle())
							->width(spLessCss::gridGetWidth(spLessCss::getLessParam('thumbL_col')))
                            ->height(spLessCss::gridGetHeight(spLessCss::getLessParam('thumbL_bl')));
				$html.= _close('span.imageWrapper');
			}

			$html.= _open('span.wrapper');
				if ($titreBloc == false) {
					$html.= _tag('span.title itemprop="name"', $article->getTitle());
				}
				//on ajoute le chapeau dans tous les cas
                                $chapeauEntier = substr($article->getChapeau(), 0, 200);
			$space = strrpos($chapeauEntier,' ');
			$chapo = substr($chapeauEntier, 0, $space).' (...)';
				$html.= _tag('span.teaser itemprop="description"', $article->$chapo);
			$html.= _close('span.wrapper');

			//On englobe l'ensemble du contenu dans un lien que l'on affiche
			echo _link($article)
				->set('.link_box')
				->title($article->getTitle())
				->text($html);
		echo _close('li');
	}
echo _close('ul');

echo _open('div.navigation.navigationBottom');
	echo _open('ul.elements');
		echo _open('li.element');
			echo _link($rubrique)->text(__('Discover our rubric'). $rubriqueTitle);
		echo _close('li');
	echo _close('ul');
echo _close('div');