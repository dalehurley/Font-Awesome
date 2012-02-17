<?php
$i = 1;

if (count($articles)) { // si nous avons des actu articles
	
		//gestion affichage du titre
    echo _tag('h4.title',$titreBloc);
?>
<ul class="elements">
<?php		
	foreach ($articles as $article) {  
        $link = '';    ?>
       <li class="element itemscope Article first last" itemtype="http://schema.org/Article" itemscope="itemscope">
        
       <?php
        
        if ($withImage == true) {
            if (($article->getImage() != NULL) and ($i <= sfConfig::get('app_nb-image'))) {
                $link .= '<span class="imageWrapper">';
                $link .= _media($article->getImage())->width($width)->set('.image');
                $link .= '</span>';
            }
        };
        $link .='<span class="wrapper">
                <span class="subWrapper">';

                   if ($titreBloc != $article->getTitle()) {
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
} else {
	// sinon on affiche la constante de la page concernée
	echo '{{actualites_du_cabinet}}';
}
