<?php

// Vars: $sidBlogArticlePager
// mise en place de la présentation de la liste des articles du cabinet
// initialisation du compteur
$i = 1;

use_stylesheet('../../sidWidgetBlogPlugin/css/styleWidgetBlog');
echo $sidBlogArticlePager->renderNavigationTop();

echo _open('ul.elements');

foreach ($sidBlogArticlePager as $sidBlogArticle) {
    // première boucle des 3 premiers articles avec photo, titre et chapeau (résumé) présentés en div
    if ($i <= 3) {
        echo _open('ul.elements');

        echo _open('li.element');

        echo _link($sidBlogArticle)->text($sidBlogArticle->getRubrique().'-'.$sidBlogArticle);

        echo _close('li');
        echo _close('ul');
    }
    // à partir du 4ème, présentation en ul li donc on crée le premier ul
    if ($i == 4) {
        echo _open('ul.elements');

        echo _open('li.element');

        echo _link($sidBlogArticle)->text($sidBlogArticle->getRubrique().'-'.$sidBlogArticle);

        echo _close('li');
    }
    // on referme le ul quand on est rendu au dernier article de la requête
    if (($i == $nbreArticle + 1) && ($i > 3)) {
        echo _open('li.element');

        echo _link($sidBlogArticle)->text($sidBlogArticle->getRubrique().'-'.$sidBlogArticle);

        echo _close('li');
        echo _close('ul');
    }
}



echo $sidBlogArticlePager->renderNavigationBottom();