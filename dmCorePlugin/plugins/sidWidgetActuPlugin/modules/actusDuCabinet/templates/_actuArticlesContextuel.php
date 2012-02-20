<?php
// vars : $articles, $nbArticles, $titreBloc, $lien, $length, $chapo, $width, $height

$i = 1;
$position = '';
if (count($articles)) { // si nous avons des actu articles
	
	
	
	//gestion affichage du titre
    echo _tag('h4.title',$titreBloc);

    echo _open('ul', array('class' => 'elements'));
    
	foreach ($articles as $article) {  
        $link = '';    
        
        switch ($i){
            case $i_max = 1: $position = 'first last';
                break;
            case '1' : $position = 'first';
                break;
            case $i_max : $position = 'last';
                break;
            default : $position = '';
        }
       
        
        echo _open('li', array('class' => 'element itemscope Article '.$position, 'itemtype' => 'http://schema.org/Article' , 'itemscope' => 'itemscope'));
        
        if ($withImage == true) {
            if (($article->getImage() != NULL) and ($i <= sfConfig::get('app_nb-image'))) {
                $link .= _open('span', array('class' => 'imageWrapper'));
                $link .= _media($article->getImage())->width($width)->set('.image')->alt($article->getTitle());
                $link .= _close('span');
            }
        };
        $link .= _open('span' , array('class' => 'wrapper'));
                    _open('span' , array('class' => 'subWrapper'));

                       if ($titreBloc != $article->getTitle()) {
                           $link .= _tag('span', array('class' => 'title itemprop name', 'itemprop' => 'name') , $article->getTitle());
                       };
                       $link .= _tag('meta' , array('content' => $article->createdAt, 'itemprop' => 'datePublished'));
                    $link .= _close('span');
                    $link .= _open('span', array('class' =>'teaser itemprop description' , 'itemprop' => 'description'));
                   if ($chapo == 0) {
                       $link .= stringTools::str_truncate($article->getResume(), $length, '(...)', true);
                   } 
                   else if ($chapo == 1) {
                       $link .= $article->getText();
                   }
                   $link .= _close('span');
               $link .= _close('span');
      
       echo _link($article)->set('.link_box')->text($link); 
       $i++;   
     ?>
       </li>
       <?php
    } ?>
</ul>
<?php
if ((isset($lien)) AND ($lien != '')) { 
        echo _open('div', array('class' => 'navigationWrapper navigationBottom'));
            echo _open('ul', array('class' => 'elements'));
                echo _tag('li', array('class' => 'element'), 
                        _link('sidActuArticle/list')->set('.link_box')->text($lien)
                        );
        echo _close('div');
    
    }
} // sinon on affiche rien
