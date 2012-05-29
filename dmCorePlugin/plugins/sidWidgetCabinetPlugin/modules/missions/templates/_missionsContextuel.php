<?php
// vars : $articles, $titreBloc, $lien, $length, $chapo, $width, $height
$i = 1;
$i_max = count($articles);
$class='';
if (count($articles)) { // si nous avons des actu articles
    //gestion affichage du titre
    echo _tag('h4.title', $titreBloc);
?>
<ul class="elements">
<?php
// initialisation des variables pour les class first et last
$nbArticle = 1;

    
    foreach ($articles as $article) {
        if ($i == 1) {
            $class = 'first';
            if ($i == $i_max)
                $class = 'first last';
        }
        elseif ($i == $i_max)
            $class = 'last';
        else
            $class = '';
        $link = ''; ?>
       <li class="element itemscope Article <?php echo $class; ?>" itemtype="http://schema.org/Article" itemscope="itemscope">
        
       <?php
        if ($article->getImage()->checkFileExists() and ($i <= sfConfig::get('app_nb-image'))) {
            $link.= '<span class="imageWrapper">';
            $link.= _media($article->getImage())->width($width)->set('.image');
            $link.= '</span>';
        };
        $link.= '<span class="wrapper">
                <span class="subWrapper">';
        if ($titreBloc != $article->getTitle()) {
            $link.= '<span class="title itemprop name" itemprop="name">' . $article->getTitle() . '</span>';
        };
        $link.= '<meta content="' . $article->createdAt . '" itemprop="datePublished">
                </span>
                <span class="teaser itemprop description" itemprop="description">';
        if ($chapo == 0) {
            $link.= stringTools::str_truncate($article->getResume() , $length, '(...)', true);
        } else if ($chapo == 1) {
            //$link.= $article->getText();
            $link.= stringTools::str_truncate($article->getText() , $length, '(...)', true);
        }
        $link.= '</span></span>';
        echo _link($article)->set('.link_box')->text($link);
        $i++;
?>
       </li>
       <?php
    } ?>
</ul>
<?php
    if ((isset($lien)) AND ($lien != '')) { ?>
        <div class="navigationWrapper navigationBottom">
            <ul class="elements">
                <li class="element first last">
                    <?php
        echo _link('mission/list')->text($lien); ?>
                </li>
        </ul>
        </div>
        <?php
    }
} // sinon on affiche rien
