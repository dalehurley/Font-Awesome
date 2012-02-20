<?php
if (count($pageCabinet)) { // si nous avons des actu articles
	
    echo _tag('h4.title',$titreBloc);
    ?>
    
<ul class="elements">

    <li class="element itemscope Article first last" itemtype="http://schema.org/Article" itemscope="itemscope">
        
        <?php
        $link = '';
        if($pageCabinet->getImage()->checkFileExists() == true){ 
        $link .= '<span class="imageWrapper">';
        $link .= _media($pageCabinet->getImage())->width($width)->set('.image')->alt($pageCabinet->getTitle());
        $link .= '</span>';
        
        };
                $link .='<span class="wrapper">
                <span class="subWrapper">';
        
                    if($titreBloc != $pageCabinet->getTitle()){
                    $link .= '<span class="title itemprop name" itemprop="name">'.$pageCabinet->getTitle().'</span>';
                    };
                    $link .= '<meta content="'.$pageCabinet->createdAt.'" itemprop="datePublished">
                </span>
                <span class="teaser itemprop description" itemprop="description">';
                $link .= stringTools::str_truncate($pageCabinet->getResume(), $length, '(...)', true).'</span></span>';
       echo _link($pageCabinet)->set('.link_box')->text($link); 
       ?>
    </li>
</ul>
<?php

 if ((isset($lien)) AND ($lien != '')) { ?>
        <div class="navigationWrapper navigationBottom">
            <ul class="elements">
                <li class="element first last">
                    <?php echo _link('pageCabinet/list')->set('.link_box')->text($lien); ?>
                </li>
            </ul>
        </div>
        <?php
    }

} ?>