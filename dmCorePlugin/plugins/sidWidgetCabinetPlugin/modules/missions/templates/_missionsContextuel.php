<?php
// vars : $articles, $nbArticles, $titlePage, $lien, $length, $chapo, $width, $height

$i = 1;
if (count($articles)) { // si nous avons des actu articles
	
	
	
	//gestion affichage du titre
    echo _tag('h4.title',$titlePage);
?>
<ul class="elements">
<?php		
	foreach ($articles as $article) {  
        $link = '';    ?>
       <li class="element itemscope Article first last" itemtype="http://schema.org/Article" itemscope="itemscope">
        
       <?php
        
        if(($article->getImage() != NULL) and ($i <= sfConfig::get('app_nb-image'))){ 
        $link .= '<span class="imageWrapper">';
        $link .= _media($article->getImage())->width($width)->set('.image');
        $link .= '</span>';
        
        };
        $link .='<span class="wrapper">
                <span class="subWrapper">';

                   if ($titlePage != $article->getTitle()) {
                       $link .= '<span class="title itemprop name" itemprop="name">' . $article->getTitle() . '</span>';
                   };
                   $link .= '<meta content="' . $article->createdAt . '" itemprop="datePublished">
                </span>
                <span class="teaser itemprop description" itemprop="description">';
                   if ($chapo == 0) {
                       $link .= stringTools::str_truncate($article->getResume(), $length, '(...)', true);
                   } 
                   else if ($chapo == 1) {
                       $link .= $article->getText();
                   }
                   $link .= '</span></span>';
      
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
                    <?php echo _link('mission/list')->set('.link_box')->text($lien); ?>
                </li>
        </ul>
        </div>
        <?php
    }
} // sinon on affiche rien
