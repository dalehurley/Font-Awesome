<?php //  var articles - titreBloc - rubrique - link - sectionName
//  foreach ($articles as $article){
//      echo _tag('h4.title', $article);
//  }
// var $articles - var $rubriqueTitle - var $rubrique - var $photoArticle - var $titreBloc
//Gestion affichage du titre du block
//Si un article affiché, son titre est affiché, sinon la rubrique est affichée

if (count($articles) != NULL) {
    echo _tag('h2.title', ucfirst($rubrique));



//Affichage des articles
    echo _open('ul.elements');
    foreach ($articles as $article) {
            if ($titreBloc == true) {
                echo _tag('h4.title', $article[0]);
            } else {
                echo _tag('h4.title', ucfirst($sectionName[$article[0]->id]));
            }
            echo '<li class="element">';
                //lien vers l'image
                $imgLink = '/_images/lea' . $article[0]->filename . '-p.jpg';
                //on vérifie que l'image existe
                $imgExist = is_file(sfConfig::get('sf_web_dir') . $imgLink);

                //composition du html contenu dans le lien (ne peux contenir que des span)
                $html = '';

                if ($photoArticle == true && $imgExist) {
                    $html.= _open('span.imageWrapper');
                    $html.= _media($imgLink)
                            ->set('.image itemprop="image"')
                            ->alt($article[0]->getTitle())
                            ->width(myUser::gridGetWidth(2, 0))
                            ->height(myUser::gridGetHeight(4, 0));
                    $html.= _close('span.imageWrapper');
                }

                $html.= _open('span.wrapper');
                if ($titreBloc == false) {
                    $html.= _tag('span.title', $article[0]);
                }
                //on ajoute le chapeau dans tous les cas
                $html.= _tag('span.teaser', $article[0]->getChapeau());
                $html.= _close('span.wrapper');

                //On englobe l'ensemble du contenu dans un lien que l'on affiche
                echo _link($article[0])
                        ->set('.link_box')
                        ->title($article[0]->getTitle())
                        ->text($html);
                echo _link($section[$article[0]->id])->text(__('All articles in the section').' '.$sectionName[$article[0]->id]);
                echo '</li>';
        }
    echo _close('ul');

//    echo _open('div.navigation.navigationBottom');
//    echo _open('ul.elements');
//    echo _open('li.element');
//    echo _link($rubrique)->text('Découvrir notre rubrique ' . $rubriqueTitle);
//    echo _close('li');
//    echo _close('ul');
//    echo _close('div');
}