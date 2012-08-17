<?php

// Vars: 
// $articles
// $widthImage
// $withImage
// $nbArticles
// $nbSections
// $sectionName
if(dmConfig::get('site_theme_version') == 'v1'){
    foreach ($sectionArticles as $section => $sectionArticle) {

        if($section) echo '<h5 class="title">'.$section.'</h5>';

        //ouverture du listing
        echo _open('ul.elements');

        $i = 0;
        $i_max = count($sectionArticle); // il faut compter le nombre de resultats pour la page en cours, count($articlePager) renvoie la taille complète du pager  

        foreach ($sectionArticle as $article) {
            $i++;
            $position = '';
            switch ($i){
                case '1' : 
                    if ($i_max == 1) $position = ' first last';
                    else $position = ' first';
                    break;
                default : 
                    if ($i == $i_max) $position = ' last';
                    else $position = '';
                    break;
            }


            //on supprime les photos après les 3 premiers articles
            $imageLink = '/_images/lea' . $article->filename . '-p.jpg';
            $imageHtml = '';
            if (is_file(sfConfig::get('sf_web_dir').$imageLink) && $i < 4 ){  // les 3 premiers articles ont une image
                $imageHtml =    
                    '<span class="imageWrapper">'.
                        '<img src="'.$imageLink.'" itemprop="image" class="image" alt="'.$article->getTitle().'">'.
                    '</span>';
            }

            //ajout de l'article
            echo 
            '<li itemtype="http://schema.org/Article" itemscope="itemscope" class="element itemscope Article'.$position.'">';
            echo _link($article)->set('.link.link_box')->text(
                    $imageHtml.
                    '<span class="wrapper">'.
                        '<span class="subWrapper">'.
                            '<span itemprop="name" class="title itemprop name">'.$article->getTitle().'</span>'.
                            '<meta content="'.$article->createdAt.'" itemprop="datePublished">'.
                        '</span>'.
                        '<span itemprop="description" class="teaser itemprop description">'.$article->getChapeau().'</span>'.
                    '</span>'
            );
            if(count($sectionName) >1){
            echo _open('span', array('class'=>'navigationWrapper navigationBottom'));
                echo _open('ul', array('class'=>'elements'));
                    echo _open('li', array('class'=>'element first last'));
                    switch ($this->context->getPage()->getRecord()->getTitle()){
                        case 'ec_echeancier': echo _link($article->getSection())->text(__('The other dates of ').$sectionName[$article->getSection()->id]);
                            break;
                        case 'ec_idees_business': echo _link($article->getSection())->text(__('The other articles of ').$sectionName[$article->getSection()->id]);
                            break;
                        case 'ec_guidecreation' : '';
                            break;
                        default: echo _link($article->getSection())->text(__('The other ').$sectionName[$article->getSection()->id])->set('.btn');
                    }
                    echo _close('li');
                echo _close('ul');
            echo _close('span');
            }
            echo '</li>';

        }

        //fermeture du listing
        echo _close('ul.elements');
        echo '<br/>';
    }
}
elseif(dmConfig::get('site_theme_version') == 'v2'){
    foreach ($sectionArticles as $section => $sectionArticle) {

        if($section) echo '<h3>'.$section.'</h3>';

        //ouverture du listing
        echo _open('ul.thumbnails');

        $i = 0;
        $i_max = count($sectionArticle); // il faut compter le nombre de resultats pour la page en cours, count($articlePager) renvoie la taille complète du pager  

        foreach ($sectionArticle as $article) {
            $i++;
            $position = '';
            switch ($i){
                case '1' : 
                    if ($i_max == 1) $position = ' first last';
                    else $position = ' first';
                    break;
                default : 
                    if ($i == $i_max) $position = ' last';
                    else $position = '';
                    break;
            }


            //on supprime les photos après les 3 premiers articles
            $imageLink = '/_images/lea' . $article->filename . '-p.jpg';
            $imageHtml = '';
            if (is_file(sfConfig::get('sf_web_dir').$imageLink) ){  
                $imageHtml =    
                        '<img src="'.$imageLink.'" alt="'.$article->getTitle().'">';
            }

            //ajout de l'article
            echo _open('li', array('class' => $position));
                echo _link($article)
                ->set('.thumbnail')
                ->text(
                                $imageHtml.

                                '<h4>'.$article->getTitle().'</h4>'.
                                _tag('div.caption','<p>'.$article->getChapeau().'</p>')

                );
            
            echo _close('li');

        }

        if(count($sectionName) >1){
            echo _open('li', array('class' => 'thumbnails'));
            switch ($this->context->getPage()->getRecord()->getTitle()){
                case 'ec_echeancier': echo _link($article->getSection())->text(__('The other dates of ').$sectionName[$article->getSection()->id])->set('.btn');
                    break;
                case 'ec_idees_business': echo _link($article->getSection())->text(__('The other articles of ').$sectionName[$article->getSection()->id])->set('.btn');
                    break;
                case 'ec_guidecreation' : '';
                    break;
                default: echo _link($article->getSection())->text(__('The other ').$sectionName[$article->getSection()->id])->set('.btn');
            }
                    
            echo _close('li');
            }
        //fermeture du listing
        echo _close('ul');
        echo '<br/>';
    }
}

